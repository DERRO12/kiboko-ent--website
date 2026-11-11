<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\CompanyMilestone;
use App\Models\CompanyValue;
use App\Models\CoverageLocation;
use App\Models\Insight;
use App\Models\InsightCategory;
use App\Models\PageSection;
use App\Models\Service;
use App\Models\Statistic;
use Illuminate\Http\Request;

/** Loads the database records each reusable page section needs. */
class SectionDataService
{
    public function load(PageSection $s, Request $r): array
    {
        return match ($s->type) {
            'stats' => ['stats' => Statistic::active()->where('group', $s->variant ?: 'home')->get()],
            'services_cards' => ['services' => Service::active()->with('media')->where('show_on_home', true)->take((int) ($s->variant ?: 4))->get()],
            'services_grid' => ['services' => Service::active()->with('media')->get()],
            'brands_carousel' => ['brands' => Brand::active()->with('media')->where('is_featured', true)->get()],
            'brands_tabs' => [
                'partners' => Brand::active()->with('media', 'category')->where('type', 'partner')->get(),
                'own' => Brand::active()->with('media', 'category')->where('type', 'own')->get(),
            ],
            'story' => ['milestones' => CompanyMilestone::orderBy('sort_order')->get()],
            'values' => ['values' => CompanyValue::active()->get()],
            'reach_map' => ['locations' => CoverageLocation::active()->get(), 'stats' => Statistic::active()->where('group', $s->variant ?: 'reach')->get()],
            'insights_list' => $this->insights($s, $r),
            default => [],
        };
    }

    protected function insights(PageSection $s, Request $r): array
    {
        $cats = InsightCategory::orderBy('sort_order')->get();
        $active = $r->query('category');
        $q = Insight::published()->with('category', 'media');
        if ($active && ($cat = $cats->firstWhere('slug', $active))) {
            $q->where('insight_category_id', $cat->id);
        }

        return ['categories' => $cats, 'activeCategory' => $active, 'insights' => $q->paginate((int) ($s->variant ?: 9))->withQueryString()];
    }
}
