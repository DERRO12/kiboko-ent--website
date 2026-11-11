<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });

        Schema::create('permissions', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $t) {
            $t->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->primary(['permission_id', 'role_id']);
        });

        Schema::table('users', function (Blueprint $t) {
            $t->foreignId('role_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $t->boolean('is_active')->default(true)->after('password');
        });

        Schema::create('media', function (Blueprint $t) {
            $t->id();
            $t->string('folder', 60)->default('general')->index();
            $t->string('title')->nullable();
            $t->string('path');
            $t->string('thumb_path')->nullable();
            $t->string('alt')->nullable();
            $t->string('mime', 100)->nullable();
            $t->unsignedInteger('size')->default(0);
            $t->unsignedInteger('width')->nullable();
            $t->unsignedInteger('height')->nullable();
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('website_settings', function (Blueprint $t) {
            $t->id();
            $t->string('group', 40)->default('general')->index();
            $t->string('key', 80)->unique();
            $t->string('label');
            $t->string('type', 20)->default('text');
            $t->text('value')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->boolean('is_published')->default(true)->index();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('page_sections', function (Blueprint $t) {
            $t->id();
            $t->foreignId('page_id')->constrained()->cascadeOnDelete();
            $t->string('type', 40);
            $t->string('eyebrow')->nullable();
            $t->string('heading')->nullable();
            $t->text('body')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->foreignId('background_media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('background_color', 20)->nullable();
            $t->string('button_label')->nullable();
            $t->string('button_url')->nullable();
            $t->string('variant', 60)->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_visible')->default(true);
            $t->timestamps();
            $t->index(['page_id', 'is_visible', 'sort_order']);
        });

        Schema::create('navigation_items', function (Blueprint $t) {
            $t->id();
            $t->string('label');
            $t->string('url');
            $t->string('location', 20)->default('header');
            $t->boolean('new_tab')->default(false);
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index(['location', 'is_active', 'sort_order']);
        });

        Schema::create('hero_sections', function (Blueprint $t) {
            $t->id();
            $t->foreignId('page_id')->constrained()->cascadeOnDelete();
            $t->string('heading');
            $t->text('text')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('button_label')->nullable();
            $t->string('button_url')->nullable();
            $t->string('button2_label')->nullable();
            $t->string('button2_url')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index(['page_id', 'is_active', 'sort_order']);
        });

        Schema::create('statistics', function (Blueprint $t) {
            $t->id();
            $t->string('group', 40)->default('home');
            $t->string('number', 20);
            $t->string('suffix', 10)->nullable();
            $t->string('label');
            $t->string('icon', 60)->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->index(['group', 'is_active', 'sort_order']);
        });

        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('summary')->nullable();
            $t->longText('description')->nullable();
            $t->longText('content')->nullable();
            $t->string('icon', 60)->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->boolean('show_on_home')->default(false);
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->index(['is_active', 'sort_order']);
        });

        Schema::create('brand_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('brands', function (Blueprint $t) {
            $t->id();
            $t->foreignId('brand_category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('type', 20)->default('partner');
            $t->string('name');
            $t->string('slug')->unique();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->text('description')->nullable();
            $t->string('website_url')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_featured')->default(false);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->index(['type', 'is_active', 'sort_order']);
        });

        Schema::create('company_values', function (Blueprint $t) {
            $t->id();
            $t->string('icon', 60)->nullable();
            $t->string('heading');
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('company_milestones', function (Blueprint $t) {
            $t->id();
            $t->string('year', 10);
            $t->string('title');
            $t->text('description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('coverage_locations', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('district')->nullable();
            $t->string('region', 40)->nullable()->index();
            $t->string('type', 30)->default('hub');
            $t->decimal('latitude', 10, 7);
            $t->decimal('longitude', 10, 7);
            $t->text('description')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('insight_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });

        Schema::create('insights', function (Blueprint $t) {
            $t->id();
            $t->foreignId('insight_category_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('excerpt')->nullable();
            $t->longText('content')->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('status', 20)->default('draft');
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
            $t->index(['status', 'published_at']);
        });

        Schema::create('contact_enquiries', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email');
            $t->string('subject');
            $t->text('message');
            $t->string('ip_address', 45)->nullable();
            $t->boolean('is_read')->default(false)->index();
            $t->text('reply')->nullable();
            $t->timestamp('replied_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('seo_metadata', function (Blueprint $t) {
            $t->id();
            $t->morphs('seoable');
            $t->string('title')->nullable();
            $t->string('description', 500)->nullable();
            $t->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete();
            $t->string('canonical_url')->nullable();
            $t->boolean('noindex')->default(false);
            $t->timestamps();
            $t->unique(['seoable_type', 'seoable_id']);
        });
    }

    public function down(): void
    {
        foreach (['seo_metadata', 'contact_enquiries', 'insights', 'insight_categories', 'coverage_locations', 'company_milestones', 'company_values', 'brands', 'brand_categories', 'services', 'statistics', 'hero_sections', 'navigation_items', 'page_sections', 'pages', 'website_settings'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropConstrainedForeignId('role_id');
            $t->dropColumn('is_active');
        });
        Schema::dropIfExists('media');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
