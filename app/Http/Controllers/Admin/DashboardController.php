<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                ['Pages', Models\Page::count(), 'fa-file-lines', 'pages'],
                ['Services', Models\Service::count(), 'fa-truck-fast', 'services'],
                ['Brands & Partners', Models\Brand::count(), 'fa-tags', 'partners'],
                ['Articles', Models\Insight::count(), 'fa-newspaper', 'insights'],
                ['Enquiries', Models\ContactEnquiry::count(), 'fa-envelope', null],
                ['Coverage Locations', Models\CoverageLocation::count(), 'fa-map-location-dot', 'locations'],
            ],
            'unread' => Models\ContactEnquiry::where('is_read', false)->count(),
            'enquiries' => Models\ContactEnquiry::latest()->take(6)->get(),
            'articles' => Models\Insight::published()->take(6)->get(),
        ]);
    }
}
