<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

/** Generic CRUD for every module defined in config/cms.php. */
class ResourceController extends Controller
{
    public static function module(string $key, bool $authorize = true): array
    {
        $c = config("cms.modules.$key");
        abort_unless($c, 404);
        if ($authorize) {
            Gate::authorize($key);
        }
        if (is_string($c['fields'])) {
            $c['fields'] = config('cms.'.$c['fields']);
        }
        $c['key'] = $key;
        $c['fields'] = collect($c['fields'])->map(fn ($f, $n) => [
            'name' => $n, 'type' => $f[0], 'label' => $f[1], 'rules' => $f[2] ?? 'nullable', 'opt' => $f[3] ?? null,
        ])->values()->all();

        return $c;
    }

    protected function query(array $c)
    {
        $q = $c['model']::query();
        foreach ($c['scope'] ?? [] as $k => $v) {
            $q->where($k, $v);
        }

        return $q;
    }

    public function index(Request $r, string $m)
    {
        $c = $this->module($m);
        $q = $this->query($c)->with($c['with'] ?? []);
        if ($r->filled('q')) {
            $term = '%'.$r->input('q').'%';
            $q->where(function ($w) use ($c, $term) {
                foreach ($c['search'] ?? [$c['title']] as $col) {
                    $w->orWhere($col, 'like', $term);
                }
            });
        }
        foreach ($c['filters'] ?? [] as $f) {
            if ($r->filled($f)) {
                $q->where($f, $r->input($f));
            }
        }
        [$col, $dir] = $c['order'] ?? ['id', 'desc'];
        $items = $q->orderBy($col, $dir)->orderBy('id')->paginate(25)->withQueryString();

        return view('admin.resource.index', compact('c', 'items'));
    }

    public function create(Request $r, string $m)
    {
        $c = $this->module($m);
        abort_if(($c['creatable'] ?? true) === false, 404);
        $item = new $c['model'];
        foreach ($c['fields'] as $f) {
            if ($r->filled($f['name'])) {
                $item->{$f['name']} = $r->query($f['name']);
            } elseif (in_array($f['name'], ['is_active', 'is_visible', 'is_published'])) {
                $item->{$f['name']} = true;
            } elseif ($f['name'] === 'user_id') {
                $item->user_id = auth()->id();
            }
        }

        return view('admin.resource.form', compact('c', 'item'));
    }

    public function store(Request $r, string $m)
    {
        $c = $this->module($m);
        abort_if(($c['creatable'] ?? true) === false, 404);

        return $this->save($r, $c, new $c['model']);
    }

    public function edit(string $m, int $id)
    {
        $c = $this->module($m);
        $item = $this->query($c)->findOrFail($id);

        return view('admin.resource.form', compact('c', 'item'));
    }

    public function update(Request $r, string $m, int $id)
    {
        $c = $this->module($m);

        return $this->save($r, $c, $this->query($c)->findOrFail($id));
    }

    public function destroy(string $m, int $id)
    {
        $c = $this->module($m);
        $item = $this->query($c)->findOrFail($id);
        if ($m === 'users' && $item->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        if ($m === 'roles' && $item->slug === 'super-admin') {
            return back()->with('error', 'The super-admin role cannot be deleted.');
        }
        $item->delete();
        Cache::flush();

        return back()->with('ok', 'Deleted.');
    }

    protected function save(Request $r, array $c, Model $item)
    {
        $rules = [];
        foreach ($c['fields'] as $f) {
            $n = $f['name'];
            $rules += match ($f['type']) {
                'checkbox' => [],
                'media' => [$n => 'nullable|integer|exists:media,id', $n.'_upload' => MediaController::RULE],
                'password' => [$n => ($item->exists ? 'nullable' : 'required').'|string|min:8|max:190'],
                'many' => [$n => 'nullable|array', $n.'.*' => 'integer'],
                default => [$n => str_replace('{id}', $item->exists ? (string) $item->id : 'NULL', $f['rules'])],
            };
        }
        if (! empty($c['seo'])) {
            $rules += ['seo_title' => 'nullable|string|max:255', 'seo_description' => 'nullable|string|max:500'];
        }
        $data = $r->validate($rules);

        DB::transaction(function () use ($r, $c, $item, $data) {
            $media = app(MediaService::class);
            foreach ($c['fields'] as $f) {
                $n = $f['name'];
                switch ($f['type']) {
                    case 'checkbox': $item->{$n} = $r->boolean($n);
                        break;
                    case 'media':
                        $item->{$n} = $r->hasFile($n.'_upload')
                            ? $media->upload($r->file($n.'_upload'), $c['key'], $item->{$c['title']} ?? null)->id
                            : ($data[$n] ?? null);
                        break;
                    case 'password':
                        if (filled($data[$n] ?? null)) {
                            $item->{$n} = $data[$n];
                        }
                        break;
                    case 'many': break;
                    default: $item->{$n} = $data[$n] ?? ($n === 'sort_order' ? 0 : null);
                }
            }
            foreach ($c['scope'] ?? [] as $k => $v) {
                $item->{$k} = $v;
            }
            if (! empty($c['slug']) && blank($item->slug)) {
                $item->slug = $this->uniqueSlug($item, (string) $item->{$c['slug']});
            }
            if ($item->getTable() === 'insights' && $item->status === 'published' && ! $item->published_at) {
                $item->published_at = now();
            }
            $item->save();
            foreach ($c['fields'] as $f) {
                if ($f['type'] === 'many') {
                    $item->{$f['name']}()->sync($data[$f['name']] ?? []);
                }
            }
            if (! empty($c['seo'])) {
                $item->seo()->updateOrCreate([], ['title' => $data['seo_title'] ?? null, 'description' => $data['seo_description'] ?? null]);
            }
        });
        Cache::flush();

        $back = $r->input('return_to');
        if ($back && str_starts_with($back, '/admin')) {
            return redirect($back)->with('ok', 'Saved.');
        }

        return redirect()->route('admin.r.index', $c['key'])->with('ok', 'Saved.');
    }

    protected function uniqueSlug(Model $item, string $source): string
    {
        $base = Str::slug($source) ?: Str::random(6);
        $slug = $base;
        $i = 2;
        $q = fn ($s) => $item->newQueryWithoutScopes()->where('slug', $s)->when($item->exists, fn ($w) => $w->whereKeyNot($item->getKey()))->exists();
        while ($q($slug)) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
