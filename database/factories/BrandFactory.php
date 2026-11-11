<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return ['type' => 'partner', 'name' => $name, 'slug' => Str::slug($name), 'website_url' => $this->faker->url(), 'is_featured' => true, 'is_active' => true];
    }
}
