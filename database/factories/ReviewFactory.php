<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
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
            'author_name' => fake()->firstName(),
            'rating' => 5,
            'body' => fake()->paragraph(),
            'reviewed_on' => fake()->date(),
            'treatment_label' => null,
            'position' => fake()->numberBetween(1, 50) * 10,
            'is_visible' => true,
        ];
    }
}
