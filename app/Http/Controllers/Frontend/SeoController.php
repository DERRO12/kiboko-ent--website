<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Insight;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap()
    {
        $xml = Cache::remember('sitemap', 3600, function () {
            $urls = Page::where('is_published', true)->get()->map(fn ($p) => [$p->url, $p->updated_at])
                ->concat(Service::where('is_active', true)->get()->map(fn ($s) => [url('what-we-do/'.$s->slug), $s->updated_at]))
                ->concat(Insight::published()->get()->map(fn ($i) => [url('insights/'.$i->slug), $i->updated_at]));

            return view('frontend.sitemap', compact('urls'))->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots()
    {
        return response("User-agent: *\nDisallow: /admin\nAllow: /\n\nSitemap: ".url('sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']);
    }
}
