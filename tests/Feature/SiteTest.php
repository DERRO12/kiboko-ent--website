<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\ContactEnquiry;
use App\Models\HeroSection;
use App\Models\Insight;
use App\Models\Page;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Models\WebsiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Cache::flush();
        $this->seed();
        $this->admin = User::first();
    }

    public function test_public_pages_render_from_database(): void
    {
        foreach (['/', '/about-us', '/what-we-do', '/brands-partners', '/our-reach', '/insights', '/contact-us'] as $url) {
            $this->get($url)->assertOk()->assertSee('Kiboko', false);
        }
        $this->get('/')->assertSee("Uganda's Trusted Distribution Partner")->assertSee('Partner With Us')->assertSee('carousel-item', false);
        $this->get('/what-we-do')->assertSee('Supply Chain Solutions');
        $this->get('/brands-partners')->assertSee('Unilever')->assertSee('Kiboko Fresh');
        $this->get('/our-reach')->assertSee('Gulu Hub')->assertSee('leaflet', false);
        $this->get('/insights?category=csr')->assertSee('community clean-up')->assertDontSee('regional depot in Mbale');
        $this->get('/what-we-do/'.Service::first()->slug)->assertOk();
        $this->get('/insights/'.Insight::first()->slug)->assertOk()->assertSee('NewsArticle', false);
        $this->get('/no-such-page')->assertNotFound();
    }

    public function test_careers_redirects_to_configured_external_site(): void
    {
        $this->get('/careers')->assertRedirect('https://jobs.kibokogroup.com');
        $this->get('/')->assertSee('href="https://jobs.kibokogroup.com"', false);
        WebsiteSetting::where('key', 'careers_url')->update(['value' => 'https://example.com/jobs']);
        Cache::flush();
        $this->get('/careers')->assertRedirect('https://example.com/jobs');
    }

    public function test_seo_endpoints(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('/our-reach', false);
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
        $this->get('/about-us')->assertSee('rel="canonical"', false)->assertSee('og:title', false);
    }

    public function test_contact_form_stores_enquiry_and_sends_email(): void
    {
        Mail::fake();
        $this->withSession(['contact_form_at' => time() - 10])
            ->post('/contact-us', ['name' => 'Jane Doe', 'email' => 'jane@example.com', 'subject' => 'Distribution', 'message' => 'We would like to partner with you.'])
            ->assertRedirect()->assertSessionHas('contact_ok');
        $this->assertDatabaseHas('contact_enquiries', ['email' => 'jane@example.com', 'is_read' => false]);

        $this->withSession(['contact_form_at' => time() - 10])->post('/contact-us', ['name' => '', 'email' => 'bad'])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        // Honeypot: silently ignored
        $this->withSession(['contact_form_at' => time() - 10])->post('/contact-us', ['name' => 'Bot', 'email' => 'b@x.com', 'subject' => 'x', 'message' => 'spam spam spam', 'website' => 'http://spam'])->assertRedirect();
        $this->assertDatabaseMissing('contact_enquiries', ['email' => 'b@x.com']);
    }

    public function test_admin_requires_login_and_role(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->post('/admin/login', ['email' => $this->admin->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $plain = User::factory()->create();
        $this->actingAs($plain)->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_every_admin_screen_loads(): void
    {
        $this->actingAs($this->admin);
        $this->get('/admin')->assertOk()->assertSee('Recent enquiries');
        foreach (array_keys(config('cms.modules')) as $m) {
            $this->get("/admin/r/$m")->assertOk();
            if ($m !== 'seo') {
                $this->get("/admin/r/$m/create")->assertOk();
            }
            $model = config("cms.modules.$m.model");
            $q = $model::query();
            foreach (config("cms.modules.$m.scope", []) as $k => $v) {
                $q->where($k, $v);
            }
            if ($first = $q->first()) {
                $this->get("/admin/r/$m/{$first->id}/edit")->assertOk();
            }
        }
        foreach (['general', 'contact', 'social', 'seo'] as $g) {
            $this->get("/admin/settings/$g")->assertOk();
        }
        $this->get('/admin/media')->assertOk();
        $this->getJson('/admin/media/picker')->assertOk()->assertJsonStructure(['data', 'next']);
        $this->get('/admin/enquiries')->assertOk();
        $this->get('/admin/profile')->assertOk();
        $this->get('/?preview=1')->assertOk();
    }

    public function test_admin_crud_with_image_upload(): void
    {
        $this->actingAs($this->admin);
        $logo = UploadedFile::fake()->image('logo.png', 2400, 1200);
        $this->post('/admin/r/partners', ['name' => 'Acme Foods', 'media_id_upload' => $logo, 'is_active' => 1, 'is_featured' => 1, 'sort_order' => 1])->assertRedirect('/admin/r/partners');
        $brand = Brand::where('name', 'Acme Foods')->firstOrFail();
        $this->assertSame('partner', $brand->type);
        $this->assertSame('acme-foods', $brand->slug);
        $this->assertNotNull($brand->media);
        $this->assertLessThanOrEqual(1920, $brand->media->width);
        Storage::disk('public')->assertExists($brand->media->path);
        $this->get('/')->assertSee('Acme Foods');

        $this->post('/admin/r/services', ['name' => 'Cold Chain', 'summary' => 'Chilled logistics', 'is_active' => 1, 'seo_title' => 'Cold chain Uganda'])->assertRedirect();
        $svc = Service::where('slug', 'cold-chain')->firstOrFail();
        $this->assertSame('Cold chain Uganda', $svc->seo->title);
        $this->get('/what-we-do')->assertSee('Cold Chain');

        $hero = HeroSection::whereHas('page', fn ($q) => $q->where('slug', 'about-us'))->first();
        $this->put("/admin/r/heroes/{$hero->id}", ['page_id' => $hero->page_id, 'heading' => 'About Kiboko Updated', 'media_id_upload' => UploadedFile::fake()->image('h.jpg', 1600, 900), 'is_active' => 1])->assertRedirect();
        $this->get('/about-us')->assertSee('About Kiboko Updated');

        $this->post('/admin/r/insights', ['title' => 'Draft story', 'status' => 'draft'])->assertRedirect();
        $this->get('/insights/draft-story')->assertNotFound();
        $draft = Insight::where('slug', 'draft-story')->first();
        $this->put("/admin/r/insights/{$draft->id}", ['title' => 'Draft story', 'slug' => 'draft-story', 'status' => 'published'])->assertRedirect();
        $this->get('/insights/draft-story')->assertOk();
        $this->delete("/admin/r/insights/{$draft->id}")->assertRedirect();
        $this->assertSoftDeleted($draft);

        $this->put('/admin/settings/general', ['settings' => WebsiteSetting::where('group', 'general')->pluck('value', 'key')->merge(['site_name' => 'Kiboko Ltd'])->all(), 'uploads' => ['logo' => UploadedFile::fake()->image('new-logo.png', 400, 120)]])->assertRedirect();
        $this->get('/')->assertSee('Kiboko Ltd');

        $page = Page::where('slug', 'our-reach')->first();
        $this->put("/admin/r/pages/{$page->id}", ['title' => 'Our Reach', 'slug' => 'our-reach', 'is_published' => 0])->assertRedirect();
        $this->get('/our-reach')->assertNotFound();
        $this->get('/our-reach?preview=1')->assertOk();
    }

    public function test_unpublished_page_hidden_from_public_but_previewable(): void
    {
        Page::where('slug', 'our-reach')->update(['is_published' => false]);
        $this->get('/our-reach')->assertNotFound();
        $this->actingAs($this->admin)->get('/our-reach?preview=1')->assertOk()->assertSee('Preview mode');
    }

    public function test_enquiry_management_and_editor_permissions(): void
    {
        Mail::fake();
        $e = ContactEnquiry::factory()->create();
        $this->actingAs($this->admin)->get("/admin/enquiries/{$e->id}")->assertOk();
        $this->assertTrue($e->fresh()->is_read);
        $this->post("/admin/enquiries/{$e->id}/reply", ['reply' => 'Thanks, we will call you.'])->assertRedirect();
        $this->assertNotNull($e->fresh()->replied_at);

        $editor = User::factory()->create(['role_id' => Role::where('slug', 'editor')->value('id')]);
        $this->actingAs($editor)->get('/admin/r/services')->assertOk();
        $this->get('/admin/r/users')->assertForbidden();
        $this->get('/admin/settings/general')->assertForbidden();
    }

    public function test_media_in_use_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $used = Service::first()->media;
        $this->delete("/admin/media/{$used->id}")->assertSessionHas('error');
        $this->assertModelExists($used);
        $this->post('/admin/media', ['files' => [UploadedFile::fake()->image('a.jpg', 800, 600)], 'folder' => 'test'])->assertSessionHas('ok');
        $new = \App\Models\Media::latest('id')->first();
        $this->assertNotNull($new->thumb_path);
        $this->delete("/admin/media/{$new->id}")->assertRedirect();
        $this->assertModelMissing($new);
        $this->post('/admin/media', ['files' => [UploadedFile::fake()->create('evil.php', 10, 'application/x-php')]])->assertSessionHasErrors();
    }
}
