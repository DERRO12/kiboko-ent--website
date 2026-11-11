<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class MediaController extends Controller
{
    public const RULE = 'nullable|file|mimes:jpg,jpeg,png,webp,gif|mimetypes:image/jpeg,image/png,image/webp,image/gif|max:5120|dimensions:max_width=8000,max_height=8000';

    public function __construct(protected MediaService $media) {}

    protected function search(Request $r)
    {
        return Media::query()
            ->when($r->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('title', 'like', '%'.$r->q.'%')->orWhere('alt', 'like', '%'.$r->q.'%')->orWhere('path', 'like', '%'.$r->q.'%')))
            ->when($r->filled('folder'), fn ($q) => $q->where('folder', $r->folder))
            ->latest();
    }

    public function index(Request $r)
    {
        Gate::authorize('media');
        $items = $this->search($r)->paginate(36)->withQueryString();
        $folders = Media::distinct()->orderBy('folder')->pluck('folder');

        return view('admin.media.index', compact('items', 'folders'));
    }

    /** JSON list for the media picker used by every image field. */
    public function picker(Request $r)
    {
        abort_unless(auth()->user()->canAccessAdmin(), 403);
        $p = $this->search($r)->paginate(30);

        return response()->json([
            'data' => $p->getCollection()->map(fn ($m) => ['id' => $m->id, 'thumb' => $m->thumb_url, 'title' => $m->label]),
            'next' => $p->hasMorePages(),
        ]);
    }

    public function store(Request $r)
    {
        Gate::authorize('media');
        $r->validate(['files' => 'required|array|max:20', 'files.*' => str_replace('nullable', 'required', self::RULE), 'folder' => 'nullable|alpha_dash|max:60', 'alt' => 'nullable|string|max:255']);
        foreach ($r->file('files') as $file) {
            $this->media->upload($file, $r->input('folder') ?: 'general', $r->input('alt'));
        }

        return back()->with('ok', count($r->file('files')).' image(s) uploaded.');
    }

    public function edit(Media $medium)
    {
        Gate::authorize('media');

        return view('admin.media.edit', ['m' => $medium, 'usage' => $this->media->usageCount($medium)]);
    }

    public function update(Request $r, Media $medium)
    {
        Gate::authorize('media');
        $data = $r->validate(['title' => 'nullable|string|max:190', 'alt' => 'nullable|string|max:255', 'folder' => 'required|alpha_dash|max:60', 'file' => self::RULE]);
        if ($r->hasFile('file')) {
            $this->media->upload($r->file('file'), $data['folder'], $data['alt'], $medium);
        }
        $medium->update(['title' => $data['title'], 'alt' => $data['alt'], 'folder' => $data['folder']]);
        Cache::flush();

        return redirect()->route('admin.media.index')->with('ok', 'Image updated.');
    }

    public function destroy(Media $medium)
    {
        Gate::authorize('media');
        if (($n = $this->media->usageCount($medium)) > 0) {
            return back()->with('error', "This image is used in $n place(s). Replace it there first.");
        }
        $this->media->delete($medium);

        return redirect()->route('admin.media.index')->with('ok', 'Image deleted.');
    }
}
