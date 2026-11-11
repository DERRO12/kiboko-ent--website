<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $name = Str::title($this->faker->unique()->words(3, true));

        return ['name' => $name, 'slug' => Str::slug($name), 'summary' => $this->faker->sentence(12), 'description' => '<p>'.$this->faker->paragraph().'</p>', 'icon' => 'fa-truck', 'sort_order' => 0, 'is_active' => true];
    }
}
