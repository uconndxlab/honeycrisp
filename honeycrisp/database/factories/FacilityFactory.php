<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'abbreviation' => fake()->unique()->regexify('[A-Z]{3}'),
            'status' => 'active',
            'address' => fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'description' => fake()->sentence(),
        ];
    }
}
