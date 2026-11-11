<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactEnquiryFactory extends Factory
{
    public function definition(): array
    {
        return ['name' => $this->faker->name(), 'email' => $this->faker->safeEmail(), 'subject' => $this->faker->sentence(4), 'message' => $this->faker->paragraph(), 'is_read' => false];
    }
}

