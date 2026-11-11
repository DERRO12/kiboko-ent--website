<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InsightFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(6);

        return ['title' => $title, 'slug' => Str::slug($title), 'excerpt' => $this->faker->sentence(15), 'content' => '<p>'.$this->faker->paragraph().'</p>', 'status' => 'published', 'published_at' => now()->subDay()];
    }
}
