<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public const GROUPS = ['general' => 'General', 'contact' => 'Contact Information', 'social' => 'Social Media', 'seo' => 'SEO Defaults'];

    public function edit(string $group)
    {
        Gate::authorize('settings');
        abort_unless(isset(self::GROUPS[$group]), 404);
        $settings = WebsiteSetting::where('group', $group)->orderBy('sort_order')->get();

        return view('admin.settings', compact('settings', 'group'));
    }

    public function update(Request $r, string $group, MediaService $media)
    {
        Gate::authorize('settings');
        $settings = WebsiteSetting::where('group', $group)->get();
        $rules = [];
        foreach ($settings as $s) {
            $rules['settings.'.$s->key] = match ($s->type) {
                'url' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:190',
                'number' => 'nullable|integer|min:0|max:100000',
                'image' => 'nullable|integer|exists:media,id',
                default => 'nullable|string|max:5000',
            };
            if ($s->type === 'image') {
                $rules['uploads.'.$s->key] = MediaController::RULE;
            }
        }
        $data = $r->validate($rules);
        DB::transaction(function () use ($settings, $data, $r, $media) {
            foreach ($settings as $s) {
                $value = $data['settings'][$s->key] ?? null;
                if ($s->type === 'image' && $r->hasFile('uploads.'.$s->key)) {
                    $value = $media->upload($r->file('uploads.'.$s->key), 'settings', $s->label)->id;
                }
                $s->update(['value' => $value]);
            }
        });
        Cache::flush();

        return back()->with('ok', 'Settings saved.');
    }
}
