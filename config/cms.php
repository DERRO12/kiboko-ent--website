<?php

use App\Models;

/*
| Admin CMS module definitions. Each module key is also the permission slug.
| Field format: name => [type, label, validation rules, options]
| Types: text, textarea, html, number, url, email, color, icon, select, belongs, many, media, checkbox, datetime, password
*/
$yesNo = 'nullable|boolean';
$sort = ['number', 'Display order', 'nullable|integer|min:0|max:99999'];

return [
    'menu' => [
        'Content' => ['pages', 'sections', 'heroes', 'navigation', 'media'],
        'Company' => ['statistics', 'services', 'milestones', 'values', 'locations'],
        'Brands' => ['brands', 'partners', 'brand_categories'],
        'Insights' => ['insight_categories', 'insights'],
        'Contact & SEO' => ['contact', 'enquiries', 'seo', 'settings'],
        'Access' => ['users', 'roles'],
    ],

    // Non-resource modules: [label, icon, route, route params]
    'custom' => [
        'media' => ['Media Library', 'fa-images', 'admin.media.index', []],
        'contact' => ['Contact Information', 'fa-address-card', 'admin.settings.edit', ['group' => 'contact'], 'settings'],
        'enquiries' => ['Contact Enquiries', 'fa-envelope', 'admin.enquiries.index', []],
        'settings' => ['Website Settings', 'fa-gear', 'admin.settings.edit', ['group' => 'general']],
    ],

    'modules' => [
        'pages' => [
            'label' => 'Pages', 'icon' => 'fa-file-lines', 'model' => Models\Page::class, 'title' => 'title', 'slug' => 'title', 'seo' => true,
            'order' => ['sort_order', 'asc'],
            'columns' => ['title' => 'Title', 'slug' => 'Slug', 'is_published' => 'Published'],
            'actions' => [['Sections', '/admin/r/sections?page_id={id}'], ['Hero', '/admin/r/heroes?page_id={id}'], ['Preview', '{url}?preview=1', true]],
            'fields' => [
                'title' => ['text', 'Page title', 'required|string|max:255'],
                'slug' => ['text', 'URL slug (blank = auto)', 'nullable|alpha_dash|max:120|unique:pages,slug,{id}'],
                'is_published' => ['checkbox', 'Published', $yesNo],
                'sort_order' => $sort,
            ],
        ],
        'sections' => [
            'label' => 'Page Sections', 'icon' => 'fa-layer-group', 'model' => Models\PageSection::class, 'title' => 'heading',
            'with' => ['page', 'media'], 'filters' => ['page_id'], 'order' => ['sort_order', 'asc'], 'search' => ['heading', 'type'],
            'columns' => ['page.title' => 'Page', 'type' => 'Type', 'heading' => 'Heading', 'media' => 'Image', 'sort_order' => 'Order', 'is_visible' => 'Visible'],
            'fields' => [
                'page_id' => ['belongs', 'Page', 'required|exists:pages,id', [Models\Page::class, 'title']],
                'type' => ['select', 'Section type', 'required|in:'.implode(',', array_keys(Models\PageSection::TYPES)), Models\PageSection::TYPES],
                'eyebrow' => ['text', 'Small label above heading', 'nullable|string|max:255'],
                'heading' => ['text', 'Heading', 'nullable|string|max:255'],
                'body' => ['html', 'Text / paragraphs', 'nullable|string|max:20000'],
                'media_id' => ['media', 'Section image'],
                'background_media_id' => ['media', 'Background image'],
                'background_color' => ['color', 'Background colour', 'nullable|regex:/^#[0-9a-fA-F]{6}$/'],
                'button_label' => ['text', 'Button label', 'nullable|string|max:80'],
                'button_url' => ['text', 'Button link', 'nullable|string|max:255'],
                'variant' => ['text', 'Variant / option (see type hint)', 'nullable|string|max:60'],
                'sort_order' => $sort,
                'is_visible' => ['checkbox', 'Visible', $yesNo],
            ],
        ],
        'heroes' => [
            'label' => 'Hero Sections', 'icon' => 'fa-panorama', 'model' => Models\HeroSection::class, 'title' => 'heading',
            'with' => ['page', 'media'], 'filters' => ['page_id'], 'order' => ['page_id', 'asc'],
            'columns' => ['page.title' => 'Page', 'heading' => 'Heading', 'media' => 'Image', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields' => [
                'page_id' => ['belongs', 'Page', 'required|exists:pages,id', [Models\Page::class, 'title']],
                'heading' => ['text', 'Heading', 'required|string|max:255'],
                'text' => ['textarea', 'Supporting text', 'nullable|string|max:1000'],
                'media_id' => ['media', 'Background image'],
                'button_label' => ['text', 'Primary button label', 'nullable|string|max:80'],
                'button_url' => ['text', 'Primary button link', 'nullable|string|max:255'],
                'button2_label' => ['text', 'Secondary button label', 'nullable|string|max:80'],
                'button2_url' => ['text', 'Secondary button link', 'nullable|string|max:255'],
                'sort_order' => $sort,
                'is_active' => ['checkbox', 'Active (several active slides on a page = auto-scrolling slider)', $yesNo],
            ],
        ],
        'navigation' => [
            'label' => 'Navigation', 'icon' => 'fa-bars', 'model' => Models\NavigationItem::class, 'title' => 'label',
            'filters' => ['location'], 'order' => ['sort_order', 'asc'],
            'columns' => ['label' => 'Label', 'url' => 'Link', 'location' => 'Menu', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields' => [
                'label' => ['text', 'Label', 'required|string|max:60'],
                'url' => ['text', 'Link (e.g. /about-us, or /careers for the careers site)', 'required|string|max:255'],
                'location' => ['select', 'Menu', 'required|in:header,footer', ['header' => 'Header', 'footer' => 'Footer']],
                'new_tab' => ['checkbox', 'Open in new tab', $yesNo],
                'sort_order' => $sort,
                'is_active' => ['checkbox', 'Active', $yesNo],
            ],
        ],
        'statistics' => [
            'label' => 'Company Statistics', 'icon' => 'fa-chart-simple', 'model' => Models\Statistic::class, 'title' => 'label',
            'filters' => ['group'], 'order' => ['sort_order', 'asc'],
            'columns' => ['group' => 'Group', 'number' => 'Number', 'label' => 'Label', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields' => [
                'group' => ['select', 'Shown on', 'required|in:home,reach', ['home' => 'Homepage', 'reach' => 'Our Reach page']],
                'number' => ['text', 'Number', 'required|string|max:20'],
                'suffix' => ['text', 'Suffix (e.g. + or %)', 'nullable|string|max:10'],
                'label' => ['text', 'Label', 'required|string|max:120'],
                'icon' => ['icon', 'Icon', 'nullable|string|max:60'],
                'sort_order' => $sort,
                'is_active' => ['checkbox', 'Active', $yesNo],
            ],
        ],
        'services' => [
            'label' => 'Services', 'icon' => 'fa-truck-fast', 'model' => Models\Service::class, 'title' => 'name', 'slug' => 'name', 'seo' => true,
            'with' => ['media'], 'order' => ['sort_order', 'asc'],
            'columns' => ['media' => 'Image', 'name' => 'Name', 'show_on_home' => 'Homepage', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'actions' => [['View', '/what-we-do/{slug}', true]],
            'fields' => [
                'name' => ['text', 'Service name', 'required|string|max:255'],
                'slug' => ['text', 'URL slug (blank = auto)', 'nullable|alpha_dash|max:120|unique:services,slug,{id}'],
                'summary' => ['textarea', 'Short description', 'nullable|string|max:500'],
                'description' => ['html', 'Detailed description', 'nullable|string|max:20000'],
                'content' => ['html', 'Additional content (optional)', 'nullable|string|max:20000'],
                'icon' => ['icon', 'Icon', 'nullable|string|max:60'],
                'media_id' => ['media', 'Featured image'],
                'show_on_home' => ['checkbox', 'Show on homepage', $yesNo],
                'sort_order' => $sort,
                'is_active' => ['checkbox', 'Active', $yesNo],
            ],
        ],
        'brands' => [
            'label' => 'Own Brands', 'icon' => 'fa-tags', 'model' => Models\Brand::class, 'title' => 'name', 'slug' => 'name',
            'scope' => ['type' => 'own'], 'with' => ['media', 'category'], 'order' => ['sort_order', 'asc'],
            'columns' => ['media' => 'Logo', 'name' => 'Name', 'category.name' => 'Category', 'is_featured' => 'Featured', 'is_active' => 'Active'],
            'fields' => 'brand_fields',
        ],
        'partners' => [
            'label' => 'Principal Partners', 'icon' => 'fa-handshake', 'model' => Models\Brand::class, 'title' => 'name', 'slug' => 'name',
            'scope' => ['type' => 'partner'], 'with' => ['media', 'category'], 'order' => ['sort_order', 'asc'],
            'columns' => ['media' => 'Logo', 'name' => 'Name', 'category.name' => 'Category', 'is_featured' => 'Featured', 'is_active' => 'Active'],
            'fields' => 'brand_fields',
        ],
        'brand_categories' => [
            'label' => 'Brand Categories', 'icon' => 'fa-folder', 'model' => Models\BrandCategory::class, 'title' => 'name', 'slug' => 'name',
            'order' => ['sort_order', 'asc'], 'columns' => ['name' => 'Name', 'slug' => 'Slug', 'sort_order' => 'Order'],
            'fields' => [
                'name' => ['text', 'Name', 'required|string|max:120'],
                'slug' => ['text', 'Slug (blank = auto)', 'nullable|alpha_dash|max:120|unique:brand_categories,slug,{id}'],
                'sort_order' => $sort,
            ],
        ],
        'milestones' => [
            'label' => 'Company Story', 'icon' => 'fa-timeline', 'model' => Models\CompanyMilestone::class, 'title' => 'title',
            'order' => ['sort_order', 'asc'], 'columns' => ['year' => 'Year', 'title' => 'Milestone', 'sort_order' => 'Order'],
            'note' => 'Milestones appear in the "Our Story" section. Edit the story text itself under Pages › About Us › Sections.',
            'fields' => [
                'year' => ['text', 'Year', 'required|string|max:10'],
                'title' => ['text', 'Milestone', 'required|string|max:255'],
                'description' => ['textarea', 'Description', 'nullable|string|max:1000'],
                'sort_order' => $sort,
            ],
        ],
        'values' => [
            'label' => 'Purpose, Vision & Values', 'icon' => 'fa-bullseye', 'model' => Models\CompanyValue::class, 'title' => 'heading',
            'order' => ['sort_order', 'asc'], 'columns' => ['heading' => 'Heading', 'icon' => 'Icon', 'sort_order' => 'Order', 'is_active' => 'Active'],
            'fields' => [
                'icon' => ['icon', 'Icon', 'nullable|string|max:60'],
                'heading' => ['text', 'Heading', 'required|string|max:120'],
                'description' => ['textarea', 'Description', 'nullable|string|max:2000'],
                'sort_order' => $sort,
                'is_active' => ['checkbox', 'Active', $yesNo],
            ],
        ],
        'locations' => [
            'label' => 'Distribution Coverage', 'icon' => 'fa-map-location-dot', 'model' => Models\CoverageLocation::class, 'title' => 'name',
            'filters' => ['region'], 'order' => ['region', 'asc'], 'search' => ['name', 'district', 'region'],
            'columns' => ['name' => 'Location', 'district' => 'District', 'region' => 'Region', 'type' => 'Type', 'is_active' => 'Active'],
            'fields' => [
                'name' => ['text', 'Location name', 'required|string|max:120'],
                'district' => ['text', 'District', 'nullable|string|max:120'],
                'region' => ['select', 'Region', 'required|in:Central,Eastern,Northern,Western,Tanzania', ['Central' => 'Central', 'Eastern' => 'Eastern', 'Northern' => 'Northern', 'Western' => 'Western', 'Tanzania' => 'Tanzania']],
                'type' => ['select', 'Type', 'required|in:hub,depot,office', ['hub' => 'Distribution hub', 'depot' => 'Depot', 'office' => 'Office']],
                'latitude' => ['text', 'Latitude', 'required|numeric|between:-12,5'],
                'longitude' => ['text', 'Longitude', 'required|numeric|between:29,41'],
                'description' => ['textarea', 'Description', 'nullable|string|max:1000'],
                'media_id' => ['media', 'Image (optional)'],
                'is_active' => ['checkbox', 'Active', $yesNo],
            ],
        ],
        'insight_categories' => [
            'label' => 'Insight Categories', 'icon' => 'fa-folder-open', 'model' => Models\InsightCategory::class, 'title' => 'name', 'slug' => 'name',
            'order' => ['sort_order', 'asc'], 'columns' => ['name' => 'Name', 'slug' => 'Slug', 'sort_order' => 'Order'],
            'fields' => [
                'name' => ['text', 'Name', 'required|string|max:120'],
                'slug' => ['text', 'Slug (blank = auto)', 'nullable|alpha_dash|max:120|unique:insight_categories,slug,{id}'],
                'sort_order' => $sort,
            ],
        ],
        'insights' => [
            'label' => 'Insights Articles', 'icon' => 'fa-newspaper', 'model' => Models\Insight::class, 'title' => 'title', 'slug' => 'title', 'seo' => true,
            'with' => ['media', 'category'], 'filters' => ['status', 'insight_category_id'], 'order' => ['created_at', 'desc'],
            'columns' => ['media' => 'Image', 'title' => 'Title', 'category.name' => 'Category', 'status' => 'Status', 'published_at' => 'Published'],
            'actions' => [['View', '/insights/{slug}', true]],
            'fields' => [
                'title' => ['text', 'Title', 'required|string|max:255'],
                'slug' => ['text', 'URL slug (blank = auto)', 'nullable|alpha_dash|max:160|unique:insights,slug,{id}'],
                'insight_category_id' => ['belongs', 'Category', 'nullable|exists:insight_categories,id', [Models\InsightCategory::class, 'name']],
                'user_id' => ['belongs', 'Author', 'nullable|exists:users,id', [Models\User::class, 'name']],
                'excerpt' => ['textarea', 'Short description', 'nullable|string|max:500'],
                'content' => ['html', 'Article content', 'nullable|string|max:60000'],
                'media_id' => ['media', 'Featured image'],
                'status' => ['select', 'Status', 'required|in:draft,published', ['draft' => 'Draft', 'published' => 'Published']],
                'published_at' => ['datetime', 'Publication date (blank = now when published)', 'nullable|date'],
            ],
        ],
        'seo' => [
            'label' => 'SEO Management', 'icon' => 'fa-magnifying-glass-chart', 'model' => Models\SeoMetadata::class, 'title' => 'title',
            'creatable' => false, 'with' => ['seoable'], 'order' => ['seoable_type', 'asc'], 'search' => ['title', 'description'],
            'columns' => ['owner_label' => 'Content', 'title' => 'SEO title', 'noindex' => 'No-index'],
            'note' => 'Default site-wide SEO values are under Website Settings › SEO.',
            'fields' => [
                'title' => ['text', 'SEO title', 'nullable|string|max:255'],
                'description' => ['textarea', 'Meta description', 'nullable|string|max:500'],
                'media_id' => ['media', 'Social share image (Open Graph)'],
                'canonical_url' => ['url', 'Canonical URL (optional)', 'nullable|url|max:255'],
                'noindex' => ['checkbox', 'Hide from search engines', $yesNo],
            ],
        ],
        'users' => [
            'label' => 'Users', 'icon' => 'fa-users', 'model' => Models\User::class, 'title' => 'name', 'with' => ['role'],
            'search' => ['name', 'email'], 'columns' => ['name' => 'Name', 'email' => 'Email', 'role.name' => 'Role', 'is_active' => 'Active'],
            'fields' => [
                'name' => ['text', 'Name', 'required|string|max:120'],
                'email' => ['email', 'Email', 'required|email|max:190|unique:users,email,{id}'],
                'password' => ['password', 'Password (min 8 characters; blank keeps current)'],
                'role_id' => ['belongs', 'Role', 'required|exists:roles,id', [Models\Role::class, 'name']],
                'is_active' => ['checkbox', 'Active', $yesNo],
            ],
        ],
        'roles' => [
            'label' => 'Roles & Permissions', 'icon' => 'fa-user-shield', 'model' => Models\Role::class, 'title' => 'name', 'slug' => 'name',
            'columns' => ['name' => 'Name', 'slug' => 'Slug'],
            'fields' => [
                'name' => ['text', 'Name', 'required|string|max:80'],
                'slug' => ['text', 'Slug (blank = auto)', 'nullable|alpha_dash|max:80|unique:roles,slug,{id}'],
                'permissions' => ['many', 'Permissions', 'nullable|array', [Models\Permission::class, 'name']],
            ],
        ],
    ],

    'brand_fields' => [
        'name' => ['text', 'Brand name', 'required|string|max:120'],
        'slug' => ['text', 'Slug (blank = auto)', 'nullable|alpha_dash|max:120|unique:brands,slug,{id}'],
        'brand_category_id' => ['belongs', 'Category', 'nullable|exists:brand_categories,id', [Models\BrandCategory::class, 'name']],
        'media_id' => ['media', 'Logo'],
        'description' => ['textarea', 'Description', 'nullable|string|max:2000'],
        'website_url' => ['url', 'Website', 'nullable|url|max:255'],
        'sort_order' => $sort,
        'is_featured' => ['checkbox', 'Featured (homepage carousel)', $yesNo],
        'is_active' => ['checkbox', 'Active', $yesNo],
    ],
];
