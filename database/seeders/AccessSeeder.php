<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccessSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = array_merge(array_keys(config('cms.modules')), ['media', 'enquiries', 'settings']);
        foreach ($slugs as $slug) {
            Permission::firstOrCreate(['slug' => $slug], ['name' => config("cms.modules.$slug.label") ?? Str::headline($slug)]);
        }

        $super = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Administrator']);
        $super->permissions()->sync(Permission::pluck('id'));
        $editor = Role::firstOrCreate(['slug' => 'editor'], ['name' => 'Content Editor']);
        $editor->permissions()->syncWithoutDetaching(Permission::whereNotIn('slug', ['users', 'roles', 'settings'])->pluck('id'));

        // Initial administrator from .env (ADMIN_EMAIL / ADMIN_PASSWORD). Never overwrites an existing account.
        $email = env('ADMIN_EMAIL', 'admin@kiboko.local');
        if (! User::where('email', $email)->exists()) {
            $password = env('ADMIN_PASSWORD') ?: Str::password(16, symbols: false);
            User::create(['name' => env('ADMIN_NAME', 'Site Administrator'), 'email' => $email, 'password' => $password, 'role_id' => $super->id, 'is_active' => true]);
            if (! env('ADMIN_PASSWORD')) {
                $this->command?->warn("Administrator created: $email  password: $password  (change it under Admin Profile)");
            }
        }
    }
}
