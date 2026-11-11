<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Insight;
use App\Models\Page;
use App\Models\Service;
use App\Services\SectionDataService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(Request $r, SectionDataService $data, string $slug = 'home')
    {
        $page = Page::where('slug', $slug)->with([
            'heroes' => fn ($q) => $q->where('is_active', true)->with('media'),
            'sections' => fn ($q) => $q->where('is_visible', true)->with('media', 'background'),
            'seo.media',
        ])->firstOrFail();

        $preview = $r->boolean('preview') && $r->user()?->canAccessAdmin();
        abort_unless($page->is_published || $preview, 404);

        $sectionData = $page->sections->mapWithKeys(fn ($s) => [$s->id => $data->load($s, $r)]);

        return view('frontend.page', compact('page', 'sectionData', 'preview'));
    }

    public function service(string $slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->with('media', 'seo.media')->firstOrFail();
        $others = Service::active()->with('media')->whereKeyNot($service->id)->take(3)->get();
        $parent = Page::where('slug', 'what-we-do')->first();

        return view('frontend.service', compact('service', 'others', 'parent'));
    }

    public function insight(string $slug)
    {
        $insight = Insight::published()->where('slug', $slug)->with('media', 'category', 'author', 'seo.media')->firstOrFail();
        $related = Insight::published()->with('media', 'category')->whereKeyNot($insight->id)->take(3)->get();

        return view('frontend.insight', compact('insight', 'related'));
    }
}
