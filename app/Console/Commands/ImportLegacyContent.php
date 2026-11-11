<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\BrandCategory;
use App\Models\CompanyMilestone;
use App\Models\CompanyValue;
use App\Models\CoverageLocation;
use App\Models\Insight;
use App\Models\InsightCategory;
use App\Models\Media;
use App\Models\Page;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\User;
use App\Models\WebsiteSetting;
use App\Services\MediaService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Replaces the sample content with Kiboko's real content and photos from the previous
 * site (C:\xampp\htdocs\kiboko-laravel). Images uploaded by an admin are never replaced:
 * only generated placeholder images (folder "seed") are swapped for real photos.
 */
class ImportLegacyContent extends Command
{
    protected $signature = 'kiboko:import-legacy {path=C:\xampp\htdocs\kiboko-laravel : Root of the previous Kiboko site}';

    protected $description = 'Import real Kiboko content and photos from the previous website';

    protected string $root;

    protected array $imported = [];

    public function handle(MediaService $media): int
    {
        $this->root = rtrim(str_replace('\\', '/', $this->argument('path')), '/').'/storage/app/public/';
        if (! is_dir($this->root.'media')) {
            $this->error('Media folder not found under '.$this->root);

            return self::FAILURE;
        }

        DB::transaction(function () use ($media) {
            $this->settings();
            $this->pages();
            $this->company();
            $this->brands();
            $this->locations();
            $this->insights();
            $this->cleanupPlaceholders($media);
        });
        Cache::flush();
        $this->info('Imported. '.count($this->imported).' photos/logos added to the media library.');

        return self::SUCCESS;
    }

    /** Copy a legacy image into the media library (resized + thumbnail), once. */
    protected function img(?string $rel, string $alt, string $folder = 'legacy'): ?Media
    {
        if (! $rel || ! is_file($this->root.$rel)) {
            return null;
        }
        if (isset($this->imported[$rel])) {
            return $this->imported[$rel];
        }
        $title = 'Kiboko: '.pathinfo($rel, PATHINFO_FILENAME);
        if ($existing = Media::where('title', $title)->first()) {
            return $this->imported[$rel] = $existing;
        }
        $file = new UploadedFile($this->root.$rel, basename($rel), null, null, true);
        $m = app(MediaService::class)->upload($file, $folder, $alt);
        $m->update(['title' => $title]);

        return $this->imported[$rel] = $m;
    }

    /** True when the record has no image or only a generated placeholder. */
    protected function placeholder($model, string $col = 'media_id'): bool
    {
        $id = $model->{$col};

        $m = $id ? Media::find($id) : null;

        return ! $m || $m->folder === 'seed' || ($m->folder === 'brands' && $m->mime === 'image/svg+xml');
    }

    protected function settings(): void
    {
        $set = fn ($k, $v) => WebsiteSetting::where('key', $k)->update(['value' => $v]);
        $set('site_name', 'Kiboko Enterprises Limited');
        $set('tagline', "Uganda's leading FMCG wholesalers and distributors");
        $set('footer_about', "Uganda's leading FMCG wholesalers and distributors since 1992, with branches across Uganda and operations in Tanzania.");
        $set('contact_address', "Plot No. 775, Kyadondo Block 244\nNamuwongo, 7th Street Industrial Area\noff new Namuwongo Road, Kampala, Uganda");
        $set('contact_phone', '+256 417 100800');
        $set('contact_email', 'kibokoinfo@kibokogroup.com');
        $set('notify_email', 'kibokoinfo@kibokogroup.com');
        $set('business_hours', "Mon – Fri: 8:00am – 5:00pm\nSat: 8:00am – 1:00pm");
        $set('social_facebook', 'https://www.facebook.com/kibokogroup/');
        $set('social_x', 'https://twitter.com/kibokogroup');
        $set('social_linkedin', 'https://www.linkedin.com/company/kiboko-group');
        $set('social_youtube', 'https://www.youtube.com/user/kibokointernational');
        $set('seo_description', 'Kiboko Enterprises Limited: Uganda’s leading FMCG wholesaler and distributor since 1992. Authorised distributor of Procter & Gamble and 50+ international brands, reaching over 24,000 outlets.');

        $extra = [
            ['general', 'header_cta_label', 'Header button label (blank = hidden)', 'text', 'Partner With Us', 90],
            ['general', 'header_cta_url', 'Header button link', 'text', '/contact-us', 91],
            ['contact', 'whatsapp_number', 'WhatsApp number (blank = no chat button)', 'text', '+256703385474', 92],
            ['general', 'store_url', 'Online store link (optional, shown in top bar)', 'url', 'https://www.kibokostore.com/', 93],
        ];
        foreach ($extra as [$group, $key, $label, $type, $value, $sort]) {
            WebsiteSetting::firstOrCreate(['key' => $key], compact('group', 'label', 'type', 'value') + ['sort_order' => $sort]);
        }
        $og = WebsiteSetting::where('key', 'og_image')->first();
        if ($og && $this->placeholder($og, 'value')) {
            $og->update(['value' => $this->img('media/2022/07/KEL-scaled.jpg', 'Kiboko Enterprises head office and warehouse, Namuwongo')?->id]);
        }
    }

    protected function pages(): void
    {
        $hq = 'media/2022/07/KEL-scaled.jpg';
        $wh = 'media/2022/07/wh2.jpg';
        $trucks = 'media/2022/07/Kiboko-Enterprises-Supply-Trucks.jpg';
        $shelf = 'media/2023/06/slidekibokopg.jpg';
        $alt = [$hq => 'Kiboko Enterprises head office and warehouse in Namuwongo', $wh => 'Pallets racked in the Kiboko warehouse', $trucks => 'Kiboko delivery trucks ready for dispatch', $shelf => 'Procter & Gamble products on a supermarket shelf', 'media/2022/08/pg.jpg' => 'Procter & Gamble product range', 'media/2022/07/KEL.jpg' => 'Kiboko Enterprises premises'];

        $heroes = [
            'home' => [
                ["Uganda's Trusted Distribution Partner", 'Renowned international brands, delivered through a network that reaches over 24,000 outlets across nearly 70% of Uganda.', $hq, 'Partner With Us', '/contact-us', 'Explore Our Capabilities', '/what-we-do'],
                ['State-of-the-Art Warehousing', 'Our Namuwongo warehouse holds 7,246 pallets in a Driving Rack system, protected by sprinklers and full fire safety.', $wh, 'Our Warehousing', '/what-we-do/warehousing-storage', 'Contact Us', '/contact-us'],
                ['A Nationwide Distribution Network', 'Retail vans, trucks and branches in Gulu, Entebbe, Lira, Jinja, Fort Portal and Arua bring our brands close to every customer.', $trucks, 'See Our Reach', '/our-reach', 'Partner With Us', '/contact-us'],
                ['Brands Uganda Loves', 'Authorised distributor of Procter & Gamble and more than 50 renowned international brands.', $shelf, 'Our Brands', '/brands-partners', 'Partner With Us', '/contact-us'],
            ],
            'about-us' => [['About Kiboko', 'Importing and distributing quality brands across Uganda since 1992, as part of the Kiboko Group of Companies.', 'media/2022/07/KEL.jpg']],
            'what-we-do' => [['What We Do', 'A comprehensive distribution platform that creates market access, builds brands and drives growth.', $wh]],
            'brands-partners' => [['Our Brands & Partners', 'Authorised distributor for Procter & Gamble and more than 50 renowned international brands, alongside our own Kibs and Lucid.', $shelf]],
            'our-reach' => [['Our Reach', 'Branches across Uganda and operations in Tanzania, serving more than 24,000 outlets.', $trucks]],
            'insights' => [['Insights', 'News, product launches, activations and community projects from Kiboko Enterprises.', 'media/2022/08/pg.jpg']],
            'contact-us' => [['Contact Us', 'We’d love to hear from you.', $hq]],
        ];

        foreach ($heroes as $slug => $slides) {
            $page = Page::where('slug', $slug)->first();
            if (! $page) {
                continue;
            }
            $existing = $page->heroes()->orderBy('sort_order')->get();
            foreach ($slides as $i => $s) {
                $hero = $existing[$i] ?? $page->heroes()->make(['sort_order' => $i, 'is_active' => true]);
                $hero->fill(['heading' => $s[0], 'text' => $s[1]] + (isset($s[3]) ? ['button_label' => $s[3], 'button_url' => $s[4], 'button2_label' => $s[5], 'button2_url' => $s[6]] : []));
                if ($this->placeholder($hero)) {
                    $hero->media_id = $this->img($s[2], $alt[$s[2]] ?? 'Kiboko Enterprises')?->id;
                }
                $hero->save();
            }
            $page->seo()->update(['description' => $slides[0][1]]);
        }

        $section = fn ($slug, $type) => Page::where('slug', $slug)->first()?->sections()->where('type', $type)->first();
        $section('home', 'services_cards')?->update(['body' => 'From the port to the shop shelf, we import, store, sell and deliver the brands Ugandans use every day.']);
        $section('home', 'brands_carousel')?->update(['body' => 'We are proud to represent Procter & Gamble and many of the world’s best-known brands in Uganda.']);
        $section('home', 'cta')?->update(['heading' => 'Grow your brand with Uganda’s leading distributor', 'body' => 'Over 30 years of experience, 24,000+ outlets and a team of 350 people ready to take your products further.']);
        if ($story = $section('about-us', 'story')) {
            $story->update(['eyebrow' => 'Our Story', 'heading' => 'Welcome to Kiboko Enterprises', 'body' => '<p>Kiboko Enterprises Limited was incorporated in May 1992 as a privately held company under the Kiboko Group of Companies. It has grown from a modest trading company dealing in roofing sheets into a leading import and distribution house of general merchandise, with annual turnover above USD 20 million.</p><p>In 1998 the company became the exclusive distributor of PHILIPS in Uganda (Lighting, Consumer Electronics and Domestic Appliances), and in 2004 it signed a distributorship agreement with Procter &amp; Gamble, the world’s leading FMCG company.</p><p>Today Kiboko is the fastest-growing distributor of FMCG and consumer durables in Uganda. Our distribution network covers every corner of the country, serving about 24,000 outlets, powered by a skilled sales force and a commitment to bringing exceptional brands to our customers.</p>']);
            if ($this->placeholder($story)) {
                $story->update(['media_id' => $this->img($wh, $alt[$wh])?->id]);
            }
        }
        $section('about-us', 'cta')?->update(['heading' => 'Partner with Kiboko', 'body' => 'Let’s bring your brand to every corner of Uganda.']);
        $section('our-reach', 'reach_map')?->update(['body' => '<p>Serving our customers and expanding our market reach is our priority. Our branches give customers convenient access to the products we distribute, and we also have operations in Tanzania.</p><p>The market is organised into zones (Kampala, Kampala Outer, West, East, North and Mid-West), each led by a Zonal Sales Manager, with sub-distributors covering the remaining territories.</p>']);
        $section('brands-partners', 'brands_tabs')?->update(['heading' => 'Brands We Distribute']);
        $section('contact-us', 'contact')?->update(['body' => 'Whether you are a brand looking for a distribution partner, a retailer or a customer, our team is ready to help. You can also visit us at our Namuwongo head office.']);
    }

    protected function company(): void
    {
        Statistic::query()->delete();
        foreach ([
            ['home', '30', '+', 'Years of Experience', 'fa-award'], ['home', '24,000', '+', 'Retail Outlets', 'fa-store'],
            ['home', '56', '+', 'Brands', 'fa-tags'], ['home', '350', '+', 'Team Members', 'fa-users'],
            ['reach', '24,000', '+', 'Retail Outlets', 'fa-store'], ['reach', '8', null, 'Branches', 'fa-warehouse'],
            ['reach', '50', null, 'Containers Received Monthly', 'fa-truck'], ['reach', '70', '%', 'Uganda Coverage', 'fa-map-location-dot'],
        ] as $i => [$group, $number, $suffix, $label, $icon]) {
            Statistic::create(compact('group', 'number', 'suffix', 'label', 'icon') + ['sort_order' => $i]);
        }

        $services = [
            'importation-sourcing' => ['We source quality products from reputable manufacturers in Egypt, Belgium, China, Turkey, the UAE, Kenya and India, receiving about 50 containers a month.', 'media/2022/08/pg.jpg',
                '<p>Kiboko Enterprises sources its supplies from world-reputable companies across Egypt, Belgium, China, Turkey, the United Arab Emirates, Kenya and India. Each source is chosen on quality, price and delivery time.</p><p>We receive about 50 containers a month from different locations. These are housed at our main warehouse and then dispatched to the market.</p><h3>Trusted by global principals</h3><ul><li>Exclusive distributor of PHILIPS in Uganda since 1998</li><li>Authorised distributor of Procter &amp; Gamble since 2004</li><li>More than 50 international brands represented</li></ul>'],
            'warehousing-storage' => ['Our Namuwongo warehouse holds 7,246 pallets in a Driving Rack system, with sprinklers and full fire safety.', 'media/2022/07/wh2.jpg',
                '<p>The main Kiboko warehouse is in Namuwongo, 7th Street Industrial Area. Its unique "Driving Rack" arrangement holds 7,246 pallets, and it is protected by a state-of-the-art fire sprinkler system and numerous fire extinguishers.</p><p>The warehouse handles receiving, stacking, arrangement and dispatch, and is organised into three divisions, each with a forklift, two supervisors, warehouse assistants, delivery vans, protective gear and firefighting equipment.</p><h3>Procter &amp; Gamble Division</h3><p>Handles all P&amp;G brands, including Always, Head &amp; Shoulders, Oral-B, Pampers, Gillette and Safeguard, plus P&amp;G activations and promotions around Uganda.</p><h3>Consumer Durables Division</h3><p>Handles electronics and domestic appliance brands such as Elekta, Lucid, Philips, Sansui and Harvels, including promotions and in-store placement.</p><h3>Star Division</h3><p>Handles food and non-food brands such as Lato, Sunvita, Dabur, Disguise, Treet, Duracell, Fogg, Himalaya, Tropicana, Promise, Peptang and Orchid Valley.</p>'],
            'sales-merchandising' => ['Sales representatives, merchandisers, supervisors and marketers who win shelf space and keep our brands visible.', 'media/2023/06/slidekibokopg.jpg',
                '<p>We are proud of a formidable workforce of sales representatives, merchandisers, sales supervisors and a marketing team that serves our clients every day.</p><p>Our teams manage brand placement in hyper and mini supermarkets, make sure each brand’s identity is respected, and drive visibility, availability and a high sales turnover.</p><h3>Organised by zones</h3><p>The market is classified into zones, each with a Zonal Sales Manager: Kampala, Kampala Outer, West, East, North and Mid-West.</p>'],
            'distribution-logistics' => ['A robust network covering nearly 70% of Uganda’s regions and more than 24,000 outlets.', 'media/2022/07/Kiboko-Enterprises-Supply-Trucks.jpg',
                '<p>Kiboko Enterprises Limited is a Fast Moving Consumer Goods distributor with a solid distribution network. We cover almost 70% of Uganda’s regions with access to more than 24,000 outlets.</p><h3>Retail vans</h3><p>A fleet of retail vans and trucks distributes to areas in and around Kampala and the districts outside Kampala.</p><h3>Branches</h3><p>Branches in Gulu, Entebbe, Lira, Jinja, Fort Portal and Arua, plus operations in Tanzania.</p><h3>Sub-distributors</h3><p>Approved sub-distributors handle distribution and sales on behalf of Kiboko under the same guidelines as Kiboko personnel.</p>'],
            'brand-building-trade-marketing' => ['Product launches, activations, sampling and promotions that build demand for the brands we carry.', 'media/2023/07/foggact.jpg',
                '<p>Our divisions plan and run product activations and promotions in strategic locations around Uganda, so that every brand reaches its targets.</p><p>Recent work includes the Fogg activations in leading Kampala supermarkets, Dabur Promise wet sampling, the Solooza Always school programme and launches for Disguise Fragrances, Philips Avent, Kibs and Lucid.</p>'],
            'supply-chain-solutions' => ['Planning, segmentation and sub-distribution that keep products flowing to every territory.', 'media/2022/07/Kiboko-Enterprises_Fort-Portal-branch.jpg',
                '<p>Our market is diverse, so it is segmented for efficient service. Each zone (Kampala, Kampala Outer, West, East, North and Mid-West) has a Zonal Sales Manager.</p><p>In areas where we are not directly active, sub-distributors are allocated a Territory Sales In-charge who reports to the Zonal Sales Manager, so supply and service stay consistent nationwide.</p>'],
        ];
        foreach ($services as $slug => [$summary, $image, $html]) {
            if (! $s = Service::where('slug', $slug)->first()) {
                continue;
            }
            $s->update(['summary' => $summary, 'description' => $html]);
            if ($this->placeholder($s)) {
                $s->update(['media_id' => $this->img($image, $s->name.' at Kiboko Enterprises')?->id]);
            }
            $s->seo()->update(['description' => $summary]);
        }

        CompanyValue::query()->delete();
        foreach ([
            ['fa-bullseye', 'Our Purpose', 'To deliver quality products and services to our customers through excellent service, careful selection of suppliers, ethical practices and continuous investment in people and technology.'],
            ['fa-eye', 'Our Vision', 'To be the preferred distribution house for domestic, regional and global brands across Africa.'],
            ['fa-handshake', 'Our Values', 'Excellent service, integrity and ethical practice, respect for every brand we carry, and a commitment to exceed the expectations of all our stakeholders.'],
        ] as $i => [$icon, $heading, $description]) {
            CompanyValue::create(compact('icon', 'heading', 'description') + ['sort_order' => $i]);
        }

        CompanyMilestone::query()->delete();
        foreach ([
            ['1992', 'Kiboko Enterprises is founded', 'Incorporated in May 1992 under the Kiboko Group of Companies, starting out trading in roofing sheets.'],
            ['1998', 'Sole distributor of PHILIPS', 'Exclusive Ugandan distributorship for PHILIPS Lighting, Consumer Electronics and Domestic Appliances.'],
            ['2004', 'Partnership with Procter & Gamble', 'Distributorship agreement with Procter & Gamble, the world’s leading FMCG company.'],
            ['2017', 'Lucid is launched', 'The Lucid brand launches in October 2017, starting with LED lights and pedestal fans.'],
            ['2022', 'Kibs snacks arrive', 'Kibs launches on 10 September 2022 with a range of potato crisps and snacks.'],
        ] as $i => [$year, $title, $description]) {
            CompanyMilestone::create(compact('year', 'title', 'description') + ['sort_order' => $i]);
        }
    }

    protected function brands(): void
    {
        $cats = [];
        foreach (['Procter & Gamble', 'Personal Care', 'Home Care', 'Foods & Beverages', 'Electricals & Batteries', 'Other Brands'] as $i => $n) {
            $cats[$n] = BrandCategory::updateOrCreate(['slug' => Str::slug($n)], ['name' => $n, 'sort_order' => $i])->id;
        }
        $keepIds = Brand::withTrashed()->whereIn('media_id', Media::where('folder', '!=', 'seed')->where('mime', '!=', 'image/svg+xml')->pluck('id'))->pluck('id')->all();

        $m = 'media/2023/06/';
        $list = [
            // [name, slug, type, category, logo, featured, website]
            ['Procter & Gamble', 'procter-gamble', 'partner', 'Procter & Gamble', $m.'pglogo.png', true, 'https://www.pg.com'],
            ['Pampers', 'pampers', 'partner', 'Procter & Gamble', $m.'pamperslogo.png', true, null],
            ['Ariel', 'ariel', 'partner', 'Procter & Gamble', $m.'ariellogo.png', true, null],
            ['Gillette', 'gillette', 'partner', 'Procter & Gamble', $m.'gilletelogo.png', true, null],
            ['Safeguard', 'safeguard', 'partner', 'Procter & Gamble', $m.'safeguardlogo.png', false, null],
            ['Downy', 'downy', 'partner', 'Procter & Gamble', $m.'downylogo.png', true, null],
            ['Oral B', 'oral-b', 'partner', 'Procter & Gamble', $m.'oralblogo.png', true, null],
            ['Always', 'always', 'partner', 'Procter & Gamble', $m.'alwayslogo.png', true, null],
            ['Pantene', 'pantene', 'partner', 'Procter & Gamble', $m.'pantenelogo.png', false, null],
            ['Head & Shoulders', 'head-shoulders', 'partner', 'Procter & Gamble', $m.'headsshoulderslogo.png', true, null],
            ['Godrej', 'godrej', 'partner', 'Personal Care', $m.'godrejlogo.png', true, null],
            ['Duracell', 'duracell', 'partner', 'Electricals & Batteries', $m.'duracelllogo.png', true, null],
            ['Realman', 'realman', 'partner', 'Personal Care', $m.'realmanlogo.png', false, null],
            ['Britannia', 'britannia', 'partner', 'Foods & Beverages', $m.'brittanialogo.png', true, null],
            ['Sunvita', 'sunvita', 'partner', 'Foods & Beverages', $m.'sunvita.jpg', false, null],
            ['Fogg', 'fogg', 'partner', 'Personal Care', $m.'fogglogo.png', true, null],
            ['Excel Chemicals', 'excel-chemicals', 'partner', 'Home Care', $m.'excellogo.png', false, null],
            ['Royalty Drinks', 'royalty-drinks', 'partner', 'Foods & Beverages', null, false, null],
            ['Inecto', 'inecto', 'partner', 'Personal Care', $m.'inectosuper.jpg', true, null],
            ['Liptomil', 'liptomil', 'partner', 'Foods & Beverages', $m.'liptomillogo.png', false, null],
            ['Olivolio', 'olivolio', 'partner', 'Foods & Beverages', $m.'olivoliologo.png', false, null],
            ['Starke', 'starke', 'partner', 'Other Brands', $m.'starkelogo.png', false, null],
            ['Ghazal', 'ghazal', 'partner', 'Other Brands', null, false, null],
            ['Le Tresor', 'le-tresor', 'partner', 'Personal Care', $m.'letresorlogo.png', false, null],
            ['Mega Growth', 'mega-growth', 'partner', 'Personal Care', $m.'megagrowthlogo.png', false, null],
            ['TCB', 'tcb', 'partner', 'Personal Care', $m.'tcblogo.png', false, null],
            ['Darling', 'darling', 'partner', 'Personal Care', $m.'darlinglogo.png', false, null],
            ['Valon', 'valon', 'partner', 'Other Brands', $m.'valonlogo.png', false, null],
            ['Climax', 'climax', 'partner', 'Other Brands', $m.'climaxlogo.png', false, null],
            ['Pride', 'pride', 'partner', 'Other Brands', $m.'pridelogo.png', false, null],
            ['Odonil', 'odonil', 'partner', 'Home Care', $m.'odonillogo.png', false, null],
            ['Treet', 'treet', 'partner', 'Personal Care', 'media/2022/08/treet.jpg', false, null],
            ['Cinthol', 'cinthol', 'partner', 'Personal Care', $m.'cinthollogo.png', false, null],
            ['Dabur', 'dabur', 'partner', 'Personal Care', 'media/2022/07/Dabur-Logo.wine_.png', true, null],
            ['Hobby', 'hobby', 'partner', 'Other Brands', 'media/2022/08/hobby.jpg', false, null],
            ['Kibs', 'kibs', 'own', 'Foods & Beverages', $m.'kibslogo.png', true, null],
            ['Lucid', 'lucid', 'own', 'Electricals & Batteries', $m.'Lucid-Logo.png', true, null],
        ];

        $desc = [
            'procter-gamble' => 'Procter & Gamble (P&G) is an American consumer goods corporation founded in 1837, specialising in cleaning agents, personal care and hygiene products. Kiboko Enterprises is the Authorised Distributor for Procter & Gamble products in Uganda.',
            'kibs' => 'Launched on 10 September 2022 by the Kiboko Group of Companies, Kibs offers potato crisps in fruit chutney, salted, BBQ, lemon & chilli, cheese & onion and salt & vinegar flavours.',
            'lucid' => 'Lucid is dedicated to enlightening lives with world-class, affordable and eco-friendly products, starting with LED lights and pedestal fans since its launch in October 2017.',
            'mega-growth' => 'Kiboko Enterprises is the Authorised Distributor for Mega Growth products in Uganda.',
        ];

        $used = [];
        foreach ($list as $i => [$name, $slug, $type, $cat, $logo, $featured, $web]) {
            $brand = Brand::withTrashed()->where('slug', $slug)->first();
            if (! $brand && $slug === 'kibs') {
                // An admin already uploaded a Kibs logo on a sample brand: reuse that record.
                $brand = Brand::withTrashed()->whereIn('id', $keepIds)->where('name', 'like', 'kibs%')->first();
            }
            $brand ??= new Brand;
            $brand->fill(['name' => $name, 'slug' => $slug, 'type' => $type, 'brand_category_id' => $cats[$cat], 'is_featured' => $featured, 'is_active' => true, 'sort_order' => $i,
                'website_url' => $web ?? $brand->website_url, 'description' => $desc[$slug] ?? "$name products are distributed across Uganda by Kiboko Enterprises."]);
            $brand->deleted_at = null;
            if ($this->placeholder($brand)) {
                $brand->media_id = $this->img($logo, $name.' logo', 'brands')?->id;
            }
            $brand->save();
            $used[] = $brand->id;
        }
        // Remove the generated sample brands (keeps any brand an admin added or gave a real logo).
        Brand::withTrashed()->whereNotIn('id', array_merge($used, $keepIds))
            ->whereIn('slug', ['unilever', 'pg', 'p-g', 'nestle', 'colgate', 'kelloggs', 'reckitt', 'bidco', 'kiboko-fresh', 'savanna-gold', 'nile-pure', 'kiboko-care'])->forceDelete();
        BrandCategory::whereIn('slug', ['foods', 'beverages', 'oral-care'])->whereDoesntHave('brands')->delete();
    }

    protected function locations(): void
    {
        CoverageLocation::query()->delete();
        foreach ([
            ['Kampala Head Office & Main Warehouse', 'Kampala', 'Central', 'office', 0.3050, 32.6155, 'Plot No. 775, Kyadondo Block 244, Namuwongo, 7th Street Industrial Area. Main warehouse with 7,246 pallet positions.', null],
            ['Entebbe Branch', 'Wakiso', 'Central', 'hub', 0.0512, 32.4637, 'Serving Entebbe and surrounding areas.', null],
            ['Jinja Branch', 'Jinja', 'Eastern', 'hub', 0.4244, 33.2041, 'Serving Jinja and the Eastern region.', null],
            ['Gulu Branch', 'Gulu', 'Northern', 'hub', 2.7746, 32.2990, 'Serving Gulu and the Acholi sub-region.', 'media/2022/07/Kiboko-Enterprises_Gulu-branch.jpg'],
            ['Lira Branch', 'Lira', 'Northern', 'hub', 2.2499, 32.8999, 'Serving Lira and the Lango sub-region.', 'media/2022/07/Kiboko-Enterprises_Lira-branch.jpg'],
            ['Arua Branch', 'Arua', 'Northern', 'hub', 3.0303, 30.9073, 'Serving Arua and West Nile.', null],
            ['Fort Portal Branch', 'Kabarole', 'Western', 'hub', 0.6710, 30.2750, 'Serving Fort Portal and the Rwenzori sub-region.', 'media/2022/07/Kiboko-Enterprises_Fort-Portal-branch.jpg'],
            ['Dar es Salaam Operations', 'Dar es Salaam', 'Tanzania', 'office', -6.7924, 39.2083, 'Kiboko operations in Tanzania.', null],
        ] as [$name, $district, $region, $type, $latitude, $longitude, $description, $image]) {
            CoverageLocation::create(compact('name', 'district', 'region', 'type', 'latitude', 'longitude', 'description') + ['media_id' => $this->img($image, $name)?->id]);
        }
    }

    protected function insights(): void
    {
        $cats = [];
        foreach (['News', 'Activations', 'Product Launches', 'CSR'] as $i => $n) {
            $cats[$n] = InsightCategory::updateOrCreate(['slug' => Str::slug($n)], ['name' => $n, 'sort_order' => $i])->id;
        }
        Insight::withTrashed()->whereIn('slug', [
            'kiboko-opens-a-new-regional-depot-in-mbale', 'weekend-market-activation-reaches-20000-shoppers', 'new-product-range-launched-in-supermarkets-nationwide',
            'staff-volunteer-at-community-clean-up-in-kampala', 'fleet-expansion-adds-30-new-delivery-vehicles', 'kiboko-signs-new-distribution-partnership',
        ])->forceDelete();

        $author = User::orderBy('id')->first();
        $posts = [
            ['Solooza Always', 'Activations', '2023-08-09', 'media/2023/08/Salooza-Press-Conference.jpg', 'Solooza Always is a reward programme where students buy Always pads, collect the empty packs and return them to school to win scholarships, laptops and a full year’s supply of Always pads. It encourages students to stock up on the menstrual protection they need to stay in school.'],
            ['Lucid Product Launch', 'Product Launches', '2023-07-05', 'media/2023/07/1581045796459.jpeg', 'Lucid is a brand dedicated to enlightening lives. Its mission is to provide world-class, affordable and eco-friendly products that meet international standards. Since its launch in October 2017, the brand has offered innovative solutions that prioritise sustainability, starting with LED lights and pedestal fans.'],
            ['Kibs Product Launch', 'Product Launches', '2023-07-05', 'media/2023/06/kibsslide.jpg', 'Launched on September 10, 2022 by the Kiboko Group of Companies, Kibs has taken the snacking world by storm. The range includes potato crisps in fruit chutney, salted, BBQ, lemon & chilli, cheese & onion and salt & vinegar flavours.'],
            ['Kira-Bukoto & Fairway Solar Street Light Project', 'CSR', '2023-07-05', 'media/2023/07/Kiboko-Enterprises_kira-bukoto-street-lights-project.jpg', 'The KEL projects department completed another vital milestone, installing solar LED street lights along Kabira, Kira-Bukoto Road and Fairway Junction as part of the KCCA Works campaign in Kampala.'],
            ['Fogg Activations', 'Activations', '2023-07-05', 'media/2023/07/foggact.jpg', 'Kiboko Enterprises Limited commenced Fogg activations in and around Kampala, focusing on busy shopping locations such as Mega Standard, Standard and Tuskys supermarkets.'],
            ['Disguise Product Launch', 'Product Launches', '2023-07-05', 'media/2023/07/disguise-2.jpg', 'Kiboko Enterprises spearheaded the launch of Disguise Fragrances in Uganda at Cooper Chimney Restaurant, hosted by MC Sarah Mubest. Disguise Fragrances is distributed and marketed by Kiboko Enterprises Limited and is available in stores near you.'],
            ['CAODA Project Luzira', 'CSR', '2023-07-05', 'media/2023/07/CAODA-luzira-project.jpg', 'Kiboko Enterprises Limited management joined the CAODA Project in Luzira, a cause that looks after children from families affected by alcohol problems. Kiboko donated 20 cartons of Orchid Valley to support the cause.'],
            ['Dabur Promise Wet Sampling', 'Activations', '2022-07-11', 'media/2022/07/Dabur-Promise-Wet-Sampling_Kiboko-Enterprises.jpg', 'Getting customers to try a new product can be challenging, especially in a crowded market. Our team ran wet sampling for Dabur Promise toothpaste so shoppers could experience the product first-hand.'],
            ['Philips Avent Launch', 'Product Launches', '2022-07-11', 'media/2022/07/Philips-Avent-Launch_Kiboko-Enterprises-3.jpg', 'Kiboko Enterprises and Philips share a long-lasting partnership, and our team took part in the launch of Philips Avent baby products in Uganda.'],
            ['P&G Low Count Pants Launch', 'Product Launches', '2022-07-11', 'media/2022/07/Pampers-Low-Count-Pants_Kiboko-Enterprises-launch.jpg', 'The Kiboko Enterprises team was glad to be part of the Procter & Gamble launch of Pampers Low Count Pants, making the brand more accessible to families across Uganda.'],
            ['New Branch Opening', 'News', '2022-07-11', 'media/2022/07/Kiboko-Enterprises_Lira-branch.jpg', 'Kiboko Enterprises is always pushing boundaries to bring our services and products closer to customers, opening new branches to serve more regions of Uganda.'],
        ];
        foreach ($posts as [$title, $cat, $date, $image, $text]) {
            $slug = Str::slug(str_replace('&', 'and', $title));
            $post = Insight::withTrashed()->firstOrNew(['slug' => $slug]);
            $post->fill(['title' => $title, 'insight_category_id' => $cats[$cat], 'user_id' => $post->user_id ?? $author?->id, 'excerpt' => Str::limit($text, 170),
                'content' => '<p>'.e($text).'</p>', 'status' => 'published', 'published_at' => $date.' 09:00:00']);
            $post->deleted_at = null;
            if ($this->placeholder($post)) {
                $post->media_id = $this->img($image, $title)?->id;
            }
            $post->save();
            $post->seo()->firstOrCreate([])->update(['description' => Str::limit($text, 160)]);
        }
    }

    /** Delete generated placeholder images that are no longer used anywhere. */
    protected function cleanupPlaceholders(MediaService $media): void
    {
        $n = 0;
        foreach (Media::where('folder', 'seed')->orWhere(fn ($q) => $q->where('folder', 'brands')->where('mime', 'image/svg+xml'))->get() as $m) {
            if ($media->usageCount($m) === 0) {
                $media->delete($m);
                $n++;
            }
        }
        $this->line("Removed $n unused placeholder images.");
    }
}
