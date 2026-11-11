<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\CompanyMilestone;
use App\Models\CompanyValue;
use App\Models\CoverageLocation;
use App\Models\Insight;
use App\Models\InsightCategory;
use App\Models\Media;
use App\Models\NavigationItem;
use App\Models\Page;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Sample content matching the Kiboko mockup. Everything here is editable in /admin.
 * Images are generated SVG placeholders stored in the media library; replace them with real photos.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Page::exists()) {
            $this->command?->info('Content already exists, skipping sample content (nothing overwritten).');

            return;
        }

        DB::transaction(function () {
            $img = $this->images();
            $this->settings($img);
            $this->navigation();
            $this->pages($img);
            $this->company($img);
            $this->brands();
            $this->locations();
            $this->insights($img);
        });
    }

    protected function images(): array
    {
        $p = fn ($k, $t, $kind, $c1, $c2, $alt) => $this->svg($k, $t, $this->photo($kind, $c1, $c2, $t), 1600, 900, $alt);

        return [
            'logo' => $this->svg('kiboko-logo', 'Kiboko logo', $this->kibokoLogo(), 360, 100, 'Kiboko Enterprises', 'branding'),
            'favicon' => $this->svg('kiboko-icon', 'Kiboko icon', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><rect x="4" y="4" width="72" height="72" rx="18" fill="#54B900"/><path d="M28 22v36M28 40l20-18M34 36l16 22" stroke="#fff" stroke-width="8" stroke-linecap="round" fill="none"/></svg>', 80, 80, 'Kiboko icon', 'branding'),
            'home1' => $p('hero-home-truck', 'Kiboko distribution truck', 'truck', '#003366', '#0b5f8a', 'Kiboko delivery truck on a Ugandan road'),
            'home2' => $p('hero-home-warehouse', 'Kiboko warehouse', 'warehouse', '#0a3d62', '#2d7d1f', 'Stocked shelves inside the Kiboko warehouse'),
            'home3' => $p('hero-home-retail', 'Retail shelf', 'store', '#003366', '#008FE5', 'FMCG products on a supermarket shelf'),
            'about' => $p('hero-about', 'Kiboko offices', 'office', '#003366', '#3a8f12', 'Kiboko head office building'),
            'story' => $p('about-story', 'Warehouse operations', 'warehouse', '#14456e', '#54B900', 'Kiboko team managing stock in the warehouse'),
            'services' => $p('hero-services', 'Distribution operations', 'network', '#003366', '#1f6f3a', 'Kiboko distribution network'),
            'brands' => $p('hero-brands', 'Supermarket aisle', 'store', '#0b2f55', '#4aa300', 'Supermarket aisle stocked with leading brands'),
            'reach' => $p('hero-reach', 'Truck in the Ugandan countryside', 'truck', '#0b4f2e', '#008FE5', 'Kiboko truck delivering across rural Uganda'),
            'insights' => $p('hero-insights', 'Kiboko team', 'team', '#003366', '#007ac2', 'Kiboko sales team at a briefing'),
            'contact' => $p('hero-contact', 'Kiboko head office', 'office', '#0a2a4f', '#54B900', 'Kiboko head office reception'),
            'svc1' => $p('service-importation', 'Importation and sourcing', 'ship', '#003366', '#008FE5', 'Containers being offloaded at port'),
            'svc2' => $p('service-warehousing', 'Warehousing and storage', 'warehouse', '#1d3b5a', '#54B900', 'Racked pallets in a modern warehouse'),
            'svc3' => $p('service-sales', 'Sales and merchandising', 'store', '#2a5d12', '#54B900', 'Merchandiser arranging products on a shelf'),
            'svc4' => $p('service-distribution', 'Distribution and logistics', 'truck', '#0d4a7a', '#54B900', 'Delivery truck leaving the depot'),
            'svc5' => $p('service-brand-building', 'Brand building and trade marketing', 'team', '#5a2d82', '#008FE5', 'Brand activation team engaging shoppers'),
            'svc6' => $p('service-supply-chain', 'Supply chain solutions', 'network', '#10233F', '#54B900', 'Supply chain planning dashboard'),
            'art1' => $p('insight-new-depot', 'New depot opening', 'warehouse', '#003366', '#4aa300', 'New Kiboko depot'),
            'art2' => $p('insight-activation', 'Market activation', 'team', '#0b5f8a', '#54B900', 'Brand activation in a Kampala market'),
            'art3' => $p('insight-launch', 'Product launch', 'store', '#3b1f66', '#008FE5', 'New product on display'),
            'art4' => $p('insight-csr', 'Community outreach', 'team', '#1f6f3a', '#54B900', 'Kiboko staff at a community event'),
            'art5' => $p('insight-fleet', 'Fleet expansion', 'truck', '#003366', '#0b8f5a', 'New delivery vehicles'),
            'art6' => $p('insight-partnership', 'New partnership', 'network', '#10233F', '#008FE5', 'Partnership signing'),
        ];
    }

    protected function settings(array $img): void
    {
        $rows = [
            ['general', 'site_name', 'Site name', 'text', 'Kiboko Enterprises'],
            ['general', 'tagline', 'Tagline', 'text', "Uganda's Trusted Distribution Partner"],
            ['general', 'logo', 'Logo', 'image', $img['logo']->id],
            ['general', 'footer_logo', 'Footer logo (optional)', 'image', null],
            ['general', 'favicon', 'Favicon', 'image', $img['favicon']->id],
            ['general', 'fallback_image', 'Fallback image (shown when an image is missing)', 'image', null],
            ['general', 'careers_url', 'Careers website (all Careers links go here)', 'url', 'https://jobs.kibokogroup.com'],
            ['general', 'hero_interval', 'Homepage slider speed (milliseconds)', 'number', '6000'],
            ['general', 'footer_about', 'Footer description', 'textarea', 'Kiboko Enterprises connects leading local and international FMCG brands to retailers and consumers in every region of Uganda.'],
            ['general', 'copyright', 'Copyright text ({year} = current year)', 'text', '© {year} Kiboko Enterprises. All rights reserved.'],
            ['contact', 'contact_address', 'Head office address', 'textarea', "Plot 00, Industrial Area\nKampala, Uganda"],
            ['contact', 'contact_phone', 'Telephone', 'text', '+256 700 000 000'],
            ['contact', 'contact_phone_2', 'Second telephone (optional)', 'text', null],
            ['contact', 'contact_email', 'Email address', 'email', 'info@kibokogroup.com'],
            ['contact', 'notify_email', 'Send website enquiries to (blank = email above)', 'email', null],
            ['contact', 'business_hours', 'Business hours', 'textarea', "Mon – Fri: 8:00am – 5:00pm\nSat: 8:00am – 1:00pm"],
            ['social', 'social_facebook', 'Facebook URL', 'url', null],
            ['social', 'social_x', 'X (Twitter) URL', 'url', null],
            ['social', 'social_linkedin', 'LinkedIn URL', 'url', null],
            ['social', 'social_instagram', 'Instagram URL', 'url', null],
            ['social', 'social_youtube', 'YouTube URL', 'url', null],
            ['seo', 'seo_description', 'Default meta description', 'textarea', 'Kiboko Enterprises is a leading Ugandan FMCG distribution company providing importation, warehousing, sales, merchandising and nationwide distribution.'],
            ['seo', 'og_image', 'Default social share image', 'image', $img['home1']->id],
        ];
        foreach ($rows as $i => [$group, $key, $label, $type, $value]) {
            WebsiteSetting::create(compact('group', 'key', 'label', 'type', 'value') + ['sort_order' => $i]);
        }
    }

    protected function navigation(): void
    {
        $header = ['Home' => '/', 'About Us' => '/about-us', 'What We Do' => '/what-we-do', 'Brands & Partners' => '/brands-partners', 'Our Reach' => '/our-reach', 'Insights' => '/insights', 'Careers' => '/careers', 'Contact' => '/contact-us'];
        $footer = ['About Us' => '/about-us', 'What We Do' => '/what-we-do', 'Brands & Partners' => '/brands-partners', 'Our Reach' => '/our-reach', 'Insights' => '/insights', 'Careers' => '/careers', 'Contact Us' => '/contact-us'];
        foreach (['header' => $header, 'footer' => $footer] as $loc => $items) {
            $i = 0;
            foreach ($items as $label => $url) {
                NavigationItem::create(['label' => $label, 'url' => $url, 'location' => $loc, 'sort_order' => ++$i]);
            }
        }
    }

    protected function pages(array $img): void
    {
        $partner = ['Partner With Us', '/contact-us'];
        $cta = fn ($h, $b) => ['type' => 'cta', 'heading' => $h, 'body' => $b, 'button_label' => $partner[0], 'button_url' => $partner[1]];

        $pages = [
            ['home', 'Home', [
                ["Uganda's Trusted Distribution Partner", 'Connecting leading brands to people across Uganda.', 'home1', 'Partner With Us', '/contact-us', 'Explore Our Capabilities', '/what-we-do'],
                ['From Brand to Basket', 'Importation, warehousing, sales and last-mile delivery, all under one roof.', 'home2', 'Partner With Us', '/contact-us', 'Explore Our Capabilities', '/what-we-do'],
                ['Reaching Every Corner of Uganda', 'Thousands of retail outlets served every week by our own fleet and sales teams.', 'home3', 'See Our Reach', '/our-reach', 'Contact Us', '/contact-us'],
            ], [
                ['type' => 'stats', 'variant' => 'home'],
                ['type' => 'services_cards', 'eyebrow' => 'What We Do', 'heading' => 'From Brand to Basket', 'body' => 'We manage the full journey of your products, from the port to the shelf, so that your brand is available, visible and growing in every market we serve.', 'button_label' => 'Explore Our Capabilities', 'button_url' => '/what-we-do', 'variant' => '4'],
                ['type' => 'brands_carousel', 'eyebrow' => 'Our Partners', 'heading' => 'Trusted Brands. Strong Partnerships.', 'button_label' => 'View All Brands', 'button_url' => '/brands-partners'],
                $cta('Ready to grow your brand in Uganda?', 'Talk to us about importation, distribution and route-to-market solutions built for your brand.'),
            ]],
            ['about-us', 'About Us', [
                ['About Kiboko', 'A proudly Ugandan distribution company built on trust, reach and execution excellence.', 'about'],
            ], [
                ['type' => 'story', 'eyebrow' => 'Our Story', 'heading' => 'Built on trust. Driven by growth.', 'media' => 'story', 'button_label' => 'Our Journey',
                    'body' => "<p>Kiboko Enterprises began with a simple idea: that great brands deserve to reach every Ugandan household, wherever they are.</p><p>From a single warehouse in Kampala, we have grown into a nationwide distribution business serving thousands of retail outlets through our own sales force, warehouses and delivery fleet.</p><p>Today we partner with leading local and international manufacturers, combining deep market knowledge with modern systems to move products efficiently from brand to basket.</p>"],
                ['type' => 'values', 'eyebrow' => 'What Drives Us', 'heading' => 'Our Purpose, Vision & Values'],
                $cta('Let’s build something great together', 'Discover how Kiboko can take your brand further across Uganda.'),
            ]],
            ['what-we-do', 'What We Do', [
                ['What We Do', 'A comprehensive distribution platform that creates market access, builds brands and drives growth.', 'services'],
            ], [
                ['type' => 'services_grid', 'eyebrow' => 'Our Capabilities', 'heading' => 'A complete route-to-market platform', 'body' => 'Six integrated capabilities that take your products from source to shelf.'],
                $cta('Need a distribution partner?', 'Our team will design a route-to-market plan for your brand.'),
            ]],
            ['brands-partners', 'Brands & Partners', [
                ['Our Brands & Partners', 'We are proud to represent some of the world’s most loved brands, alongside our own growing portfolio.', 'brands'],
            ], [
                ['type' => 'brands_tabs', 'eyebrow' => 'Our Portfolio', 'heading' => 'Brands Uganda Loves', 'button_label' => '& Many More', 'button_url' => '/contact-us'],
                $cta('Become a Kiboko partner', 'Join the principals who trust Kiboko to grow their brands in Uganda.'),
            ]],
            ['our-reach', 'Our Reach', [
                ['Our Reach', 'Nationwide coverage through strategically located hubs, depots and a modern delivery fleet.', 'reach'],
            ], [
                ['type' => 'reach_map', 'eyebrow' => 'Our Network', 'heading' => 'Nationwide Coverage', 'variant' => 'reach',
                    'body' => '<p>Our distribution hubs and depots cover the Central, Eastern, Northern and Western regions, making sure products reach shops, supermarkets, wholesalers and markets on time.</p>'],
            ]],
            ['insights', 'Insights', [
                ['Insights', 'News, activations, product launches and community stories from across Kiboko.', 'insights'],
            ], [
                ['type' => 'insights_list', 'eyebrow' => 'Latest Updates', 'heading' => 'News & Stories', 'variant' => '9'],
            ]],
            ['contact-us', 'Contact Us', [
                ['Contact Us', 'We’d love to hear from you.', 'contact'],
            ], [
                ['type' => 'contact', 'eyebrow' => 'Get in Touch', 'heading' => 'Talk to our team', 'body' => 'Whether you are a brand looking for a distribution partner, a retailer or a job seeker, our team is ready to help.', 'button_label' => 'Send Message'],
            ]],
        ];

        foreach ($pages as $i => [$slug, $title, $heroes, $sections]) {
            $page = Page::create(['slug' => $slug, 'title' => $title, 'sort_order' => $i, 'is_published' => true]);
            $page->seo()->update(['title' => $slug === 'home' ? null : $title, 'description' => $heroes[0][1]]);
            foreach ($heroes as $j => $h) {
                $page->heroes()->create(['heading' => $h[0], 'text' => $h[1], 'media_id' => $img[$h[2]]->id, 'button_label' => $h[3] ?? null, 'button_url' => $h[4] ?? null, 'button2_label' => $h[5] ?? null, 'button2_url' => $h[6] ?? null, 'sort_order' => $j]);
            }
            foreach ($sections as $j => $s) {
                $media = isset($s['media']) ? $img[$s['media']]->id : null;
                unset($s['media']);
                $page->sections()->create($s + ['media_id' => $media, 'sort_order' => ($j + 1) * 10]);
            }
        }
    }

    protected function company(array $img): void
    {
        $stats = [
            ['home', '15', '+', 'Years of Experience', 'fa-award'], ['home', '12,000', '+', 'Retail Outlets', 'fa-store'],
            ['home', '50', '+', 'Brands', 'fa-tags'], ['home', '400', '+', 'Team Members', 'fa-users'],
            ['reach', '12,000', '+', 'Retail Outlets', 'fa-store'], ['reach', '8', null, 'Distribution Hubs', 'fa-warehouse'],
            ['reach', '120', '+', 'Delivery Vehicles', 'fa-truck'], ['reach', '100', '%', 'Nationwide Coverage', 'fa-map-location-dot'],
        ];
        foreach ($stats as $i => [$group, $number, $suffix, $label, $icon]) {
            Statistic::create(compact('group', 'number', 'suffix', 'label', 'icon') + ['sort_order' => $i]);
        }

        $services = [
            ['Importation & Sourcing', 'fa-ship', 'svc1', 'We source and import quality products from global manufacturers, handling clearance, compliance and freight.', 'Our importation team manages supplier relationships, shipping, customs clearance, standards certification and landed-cost control, giving principals a reliable, compliant flow of stock into Uganda.'],
            ['Warehousing & Storage', 'fa-warehouse', 'svc2', 'Modern, secure warehouses with stock control systems that keep products safe and ready to move.', 'Our warehouses are racked, secured and run on a warehouse management system with batch and expiry tracking, first-expiry-first-out picking and regular stock audits.'],
            ['Sales & Merchandising', 'fa-store', 'svc3', 'Dedicated sales and merchandising teams that win shelf space and keep products visible.', 'Route-based sales representatives and merchandisers visit outlets on fixed journey plans to take orders, secure displays, enforce planograms and collect market intelligence.'],
            ['Distribution & Logistics', 'fa-truck-fast', 'svc4', 'Reliable last-mile delivery to wholesalers, supermarkets and retailers across Uganda.', 'Our own fleet, supported by vetted partners, delivers on scheduled routes from regional hubs, with proof-of-delivery and real-time tracking.'],
            ['Brand Building & Trade Marketing', 'fa-bullhorn', 'svc5', 'Activations, promotions and trade programmes that grow demand for your brands.', 'We plan and execute consumer activations, sampling, retailer incentive programmes and point-of-sale campaigns, and report on their impact.'],
            ['Supply Chain Solutions', 'fa-diagram-project', 'svc6', 'End-to-end planning, forecasting and reporting that make your supply chain efficient.', 'Demand planning, replenishment, secondary-sales reporting and data insight help principals reduce stock-outs, control costs and plan growth.'],
        ];
        foreach ($services as $i => [$name, $icon, $m, $summary, $desc]) {
            $s = Service::create(['name' => $name, 'slug' => Str::slug($name), 'icon' => $icon, 'media_id' => $img[$m]->id, 'summary' => $summary,
                'description' => "<p>$desc</p><h3>What you get</h3><ul><li>A dedicated account team</li><li>Clear monthly performance reporting</li><li>Coverage in all regions of Uganda</li></ul>",
                'show_on_home' => $i < 4, 'sort_order' => $i]);
            $s->seo()->update(['description' => $summary]);
        }

        foreach ([
            ['fa-bullseye', 'Our Purpose', 'To connect great brands with the people of Uganda, improving lives through access to quality products.'],
            ['fa-eye', 'Our Vision', 'To be the most trusted and efficient distribution partner in East Africa.'],
            ['fa-handshake', 'Our Values', 'Integrity, customer focus, teamwork, excellence in execution and respect for our communities.'],
        ] as $i => [$icon, $heading, $description]) {
            CompanyValue::create(compact('icon', 'heading', 'description') + ['sort_order' => $i]);
        }

        foreach ([
            ['2010', 'Founded in Kampala', 'Kiboko starts operations from a single warehouse.'],
            ['2014', 'First regional depot', 'Expansion into Eastern Uganda.'],
            ['2018', 'International principals', 'Partnerships with leading global FMCG manufacturers.'],
            ['2022', 'Fleet of 100+ vehicles', 'Own delivery fleet reaches every region.'],
            ['2025', 'Nationwide coverage', 'Eight hubs serving thousands of outlets.'],
        ] as $i => [$year, $title, $description]) {
            CompanyMilestone::create(compact('year', 'title', 'description') + ['sort_order' => $i]);
        }
    }

    protected function brands(): void
    {
        $cats = collect(['Personal Care', 'Home Care', 'Foods', 'Beverages', 'Oral Care'])->mapWithKeys(fn ($n, $i) => [$n => BrandCategory::create(['name' => $n, 'slug' => Str::slug($n), 'sort_order' => $i])->id]);
        $list = [
            ['partner', 'Unilever', 'Personal Care', '#1F36C7', 'https://www.unilever.com'],
            ['partner', 'P&G', 'Home Care', '#003DA5', 'https://www.pg.com'],
            ['partner', 'Nestlé', 'Foods', '#63513D', 'https://www.nestle.com'],
            ['partner', 'Colgate', 'Oral Care', '#D2010D', 'https://www.colgatepalmolive.com'],
            ['partner', "Kellogg's", 'Foods', '#D3112F', 'https://www.kelloggs.com'],
            ['partner', 'Reckitt', 'Home Care', '#E5007E', 'https://www.reckitt.com'],
            ['partner', 'Bidco', 'Foods', '#0A7A3B', 'https://www.bidcoafrica.com'],
            ['own', 'Kiboko Fresh', 'Foods', '#54B900', null],
            ['own', 'Savanna Gold', 'Foods', '#C98A00', null],
            ['own', 'Nile Pure', 'Beverages', '#008FE5', null],
            ['own', 'Kiboko Care', 'Personal Care', '#003366', null],
        ];
        foreach ($list as $i => [$type, $name, $cat, $color, $url]) {
            $logo = $this->svg('logo-'.Str::slug($name), $name.' logo', $this->wordmark($name, $color), 400, 200, $name.' logo', 'brands');
            Brand::create(['type' => $type, 'name' => $name, 'slug' => Str::slug($name), 'brand_category_id' => $cats[$cat], 'media_id' => $logo->id,
                'description' => $type === 'own' ? "$name is part of the Kiboko own-brand portfolio." : "$name products distributed by Kiboko across Uganda.",
                'website_url' => $url, 'sort_order' => $i, 'is_featured' => $type === 'partner' || $i < 9]);
        }
    }

    protected function locations(): void
    {
        foreach ([
            ['Kampala Head Office & Hub', 'Kampala', 'Central', 'hub', 0.3476, 32.5825, 'Head office and main national distribution centre.'],
            ['Masaka Depot', 'Masaka', 'Central', 'depot', -0.3338, 31.7341, 'Serving the greater Masaka region.'],
            ['Jinja Hub', 'Jinja', 'Eastern', 'hub', 0.4244, 33.2042, 'Eastern region distribution hub.'],
            ['Mbale Hub', 'Mbale', 'Eastern', 'hub', 1.0827, 34.1750, 'Serving Elgon and Bukedi sub-regions.'],
            ['Soroti Depot', 'Soroti', 'Eastern', 'depot', 1.7146, 33.6111, 'Serving Teso sub-region.'],
            ['Gulu Hub', 'Gulu', 'Northern', 'hub', 2.7724, 32.2881, 'Northern region distribution hub.'],
            ['Lira Depot', 'Lira', 'Northern', 'depot', 2.2499, 32.8999, 'Serving Lango sub-region.'],
            ['Arua Hub', 'Arua', 'Northern', 'hub', 3.0201, 30.9111, 'Serving West Nile.'],
            ['Mbarara Hub', 'Mbarara', 'Western', 'hub', -0.6072, 30.6545, 'Western region distribution hub.'],
            ['Fort Portal Depot', 'Kabarole', 'Western', 'depot', 0.6710, 30.2750, 'Serving the Rwenzori sub-region.'],
            ['Hoima Hub', 'Hoima', 'Western', 'hub', 1.4331, 31.3524, 'Serving Bunyoro sub-region.'],
            ['Kabale Depot', 'Kabale', 'Western', 'depot', -1.2486, 29.9899, 'Serving Kigezi sub-region.'],
        ] as [$name, $district, $region, $type, $latitude, $longitude, $description]) {
            CoverageLocation::create(compact('name', 'district', 'region', 'type', 'latitude', 'longitude', 'description'));
        }
    }

    protected function insights(array $img): void
    {
        $cats = collect(['News', 'Activations', 'Product Launches', 'CSR'])->mapWithKeys(fn ($n, $i) => [$n => InsightCategory::create(['name' => $n, 'slug' => Str::slug($n), 'sort_order' => $i])->id]);
        $author = User::first();
        foreach ([
            ['Kiboko opens a new regional depot in Mbale', 'News', 'art1', 'Our new Mbale depot brings faster deliveries to retailers across Eastern Uganda.', 5],
            ['Weekend market activation reaches 20,000 shoppers', 'Activations', 'art2', 'Sampling, games and great offers drew crowds at markets across Kampala.', 12],
            ['New product range launched in supermarkets nationwide', 'Product Launches', 'art3', 'A new range from one of our principal partners is now on shelves countrywide.', 20],
            ['Staff volunteer at community clean-up in Kampala', 'CSR', 'art4', 'Over 100 Kiboko team members joined hands to give back to our community.', 28],
            ['Fleet expansion adds 30 new delivery vehicles', 'News', 'art5', 'More vehicles mean more frequent deliveries to upcountry outlets.', 40],
            ['Kiboko signs new distribution partnership', 'News', 'art6', 'A new principal partner joins the Kiboko portfolio.', 55],
        ] as [$title, $cat, $m, $excerpt, $days]) {
            $i = Insight::create(['title' => $title, 'slug' => Str::slug($title), 'insight_category_id' => $cats[$cat], 'user_id' => $author?->id, 'media_id' => $img[$m]->id,
                'excerpt' => $excerpt, 'status' => 'published', 'published_at' => now()->subDays($days),
                'content' => "<p>$excerpt</p><p>This is sample article content. Edit or replace it from the admin dashboard under Insights Articles.</p><h3>Why it matters</h3><p>Every step we take is about bringing great brands closer to the people of Uganda, more reliably and more efficiently.</p>"]);
            $i->seo()->update(['description' => $excerpt]);
        }
    }

    // ---------- Placeholder artwork (SVG, stored in the media library) ----------

    protected function svg(string $key, string $title, string $svg, int $w, int $h, string $alt, string $folder = 'seed'): Media
    {
        $path = "media/$folder/$key.svg";
        Storage::disk('public')->put($path, $svg);

        return Media::create(['folder' => $folder, 'title' => $title, 'path' => $path, 'alt' => $alt, 'mime' => 'image/svg+xml', 'size' => strlen($svg), 'width' => $w, 'height' => $h]);
    }

    protected function kibokoLogo(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" width="360" height="100" viewBox="0 0 360 100"><rect x="4" y="14" width="72" height="72" rx="18" fill="#54B900"/><path d="M28 32v36M28 50l20-18M34 46l16 22" stroke="#fff" stroke-width="8" stroke-linecap="round" fill="none"/><text x="90" y="60" font-family="Arial,Helvetica,sans-serif" font-weight="800" font-size="44" fill="#54B900">KIBOKO</text><text x="93" y="84" font-family="Arial,Helvetica,sans-serif" font-weight="600" font-size="15" letter-spacing="5.5" fill="#003366">ENTERPRISES</text></svg>';
    }

    protected function wordmark(string $name, string $color): string
    {
        $fs = min(64, (int) (620 / max(4, mb_strlen($name))));
        $n = htmlspecialchars($name, ENT_XML1);

        return '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="200" viewBox="0 0 400 200"><rect x="30" y="40" width="340" height="120" rx="60" fill="'.$color.'" fill-opacity=".08"/><text x="200" y="'.(100 + $fs / 3).'" text-anchor="middle" font-family="Arial,Helvetica,sans-serif" font-weight="800" font-size="'.$fs.'" fill="'.$color.'">'.$n.'</text></svg>';
    }

    protected function photo(string $kind, string $c1, string $c2, string $title): string
    {
        $s = '';
        $w = 'fill="#fff"';
        switch ($kind) {
            case 'truck':
                $s .= '<path d="M0 620 Q300 540 640 600 T1600 560 V900 H0Z" fill="#fff" fill-opacity=".1"/><rect y="760" width="1600" height="140" fill="#111c27" fill-opacity=".5"/><path d="M0 830 H1600" stroke="#fff" stroke-opacity=".5" stroke-width="6" stroke-dasharray="60 50"/>';
                $s .= '<g transform="translate(820 470)"><rect width="470" height="240" rx="12" '.$w.'/><rect x="22" y="24" width="426" height="86" rx="8" fill="#54B900"/><text x="235" y="83" text-anchor="middle" font-family="Arial" font-weight="800" font-size="48" fill="#fff">KIBOKO</text><path d="M470 70 h120 l80 86 v84 h-200z" fill="#eef3f7"/><path d="M500 92 h80 l50 56 h-130z" fill="#8fbcdc"/>';
                foreach ([110, 380, 580] as $x) {
                    $s .= '<circle cx="'.$x.'" cy="250" r="46" fill="#1b1f24"/><circle cx="'.$x.'" cy="250" r="18" fill="#9aa5b1"/>';
                }
                $s .= '</g>';
                break;
            case 'warehouse':
                for ($c = 0; $c < 4; $c++) {
                    $x = 760 + $c * 200;
                    $s .= '<rect x="'.$x.'" y="180" width="12" height="620" fill="#fff" fill-opacity=".5"/><rect x="'.($x + 168).'" y="180" width="12" height="620" fill="#fff" fill-opacity=".5"/>';
                    for ($r = 0; $r < 4; $r++) {
                        $y = 230 + $r * 150;
                        $s .= '<rect x="'.$x.'" y="'.($y + 110).'" width="180" height="10" fill="#f39c12" fill-opacity=".8"/>';
                        foreach ([0, 1] as $b) {
                            $s .= '<rect x="'.($x + 20 + $b * 75).'" y="'.($y + 30 + ($r + $b + $c) % 2 * 15).'" width="64" height="'.(80 - ($r + $b + $c) % 2 * 15).'" rx="4" fill="#d9a066" fill-opacity=".9"/>';
                        }
                    }
                }
                $s .= '<rect y="800" width="1600" height="100" fill="#000" fill-opacity=".2"/>';
                break;
            case 'store':
                $colors = ['#e74c3c', '#f1c40f', '#2ecc71', '#3498db', '#9b59b6', '#ffffff', '#e67e22'];
                for ($r = 0; $r < 5; $r++) {
                    $y = 160 + $r * 140;
                    $s .= '<rect x="720" y="'.($y + 110).'" width="860" height="14" fill="#fff" fill-opacity=".7"/>';
                    for ($i = 0; $i < 12; $i++) {
                        $h = 60 + (($i * 7 + $r * 3) % 4) * 12;
                        $s .= '<rect x="'.(735 + $i * 70).'" y="'.($y + 110 - $h).'" width="54" height="'.$h.'" rx="6" fill="'.$colors[($i + $r) % 7].'" fill-opacity=".85"/>';
                    }
                }
                break;
            case 'team':
                foreach ([[880, 1], [1040, 1.1], [1200, 1], [1360, 1.05], [960, 0.85], [1280, 0.9]] as $k => [$x, $sc]) {
                    $y = $k > 3 ? 470 : 420;
                    $s .= '<g transform="translate('.$x.' '.$y.') scale('.$sc.')"><circle r="58" fill="#fff" fill-opacity=".85"/><path d="M-100 330 Q-100 90 0 90 Q100 90 100 330Z" fill="'.($k % 2 ? '#54B900' : '#ffffff').'" fill-opacity="'.($k % 2 ? '.95' : '.7').'"/></g>';
                }
                break;
            case 'office':
                $s .= '<rect x="860" y="140" width="520" height="680" fill="#fff" fill-opacity=".15"/><rect x="1380" y="320" width="180" height="500" fill="#fff" fill-opacity=".1"/>';
                for ($r = 0; $r < 9; $r++) {
                    for ($c = 0; $c < 6; $c++) {
                        $s .= '<rect x="'.(890 + $c * 82).'" y="'.(175 + $r * 68).'" width="58" height="44" fill="#bfe3ff" fill-opacity="'.(($r + $c) % 3 ? '.35' : '.7').'"/>';
                    }
                }
                $s .= '<rect x="1060" y="700" width="120" height="120" fill="#54B900"/><rect y="820" width="1600" height="80" fill="#000" fill-opacity=".25"/>';
                break;
            case 'ship':
                $s .= '<rect y="700" width="1600" height="200" fill="#0b2b4a" fill-opacity=".6"/><path d="M720 640 h820 l-70 120 h-680z" fill="#1b2633"/>';
                $colors = ['#e74c3c', '#54B900', '#f39c12', '#008FE5', '#ecf0f1'];
                for ($r = 0; $r < 4; $r++) {
                    for ($c = 0; $c < 6; $c++) {
                        $s .= '<rect x="'.(770 + $c * 122).'" y="'.(580 - $r * 62).'" width="116" height="56" fill="'.$colors[($r * 2 + $c) % 5].'" fill-opacity=".9"/>';
                    }
                }
                break;
            default: // network
                $pts = [[900, 300], [1100, 220], [1300, 340], [1450, 220], [1000, 520], [1250, 560], [1450, 500], [1150, 720], [850, 680]];
                foreach ([[0, 1], [1, 2], [2, 3], [0, 4], [4, 5], [5, 2], [5, 6], [3, 6], [4, 8], [5, 7], [7, 8]] as [$a, $b]) {
                    $s .= '<line x1="'.$pts[$a][0].'" y1="'.$pts[$a][1].'" x2="'.$pts[$b][0].'" y2="'.$pts[$b][1].'" stroke="#fff" stroke-opacity=".5" stroke-width="4"/>';
                }
                foreach ($pts as $i => [$x, $y]) {
                    $s .= '<circle cx="'.$x.'" cy="'.$y.'" r="'.($i % 3 ? 22 : 34).'" fill="'.($i % 2 ? '#54B900' : '#ffffff').'"/>';
                }
        }
        $t = htmlspecialchars($title, ENT_XML1);

        return '<svg xmlns="http://www.w3.org/2000/svg" width="1600" height="900" viewBox="0 0 1600 900"><defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="'.$c1.'"/><stop offset="1" stop-color="'.$c2.'"/></linearGradient></defs><rect width="1600" height="900" fill="url(#g)"/><circle cx="1500" cy="80" r="260" fill="#fff" fill-opacity=".05"/>'.$s.'<text x="1570" y="880" text-anchor="end" font-family="Arial" font-size="22" fill="#fff" fill-opacity=".6">'.$t.' · placeholder image</text></svg>';
    }
}
