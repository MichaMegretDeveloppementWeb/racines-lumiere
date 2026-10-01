<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TreatmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentCategory>
 */
class TreatmentCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(3),
            'name' => fake()->words(3, true),
            'subtitle' => null,
            'description' => fake()->sentence(),
            'is_signature' => false,
            'is_featured' => false,
            'booking_url' => null,
            'position' => fake()->numberBetween(1, 50) * 10,
            'is_visible' => true,
        ];
    }
}
