<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'name' => fake()->company(),
            'tagline' => fake()->sentence(4),
            'long_text' => implode("\n\n", fake()->paragraphs(3)),
            'short_text' => fake()->sentence(),
            'role_text' => fake()->sentence(),
            'logo_path' => null,
            'products_url' => null,
            'position' => fake()->numberBetween(1, 50) * 10,
            'is_visible' => true,
        ];
    }
}
