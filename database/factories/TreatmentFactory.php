<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Treatment;
use App\Models\TreatmentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Treatment>
 */
class TreatmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'treatment_category_id' => TreatmentCategory::factory(),
            'slug' => fake()->unique()->slug(3),
            'name' => fake()->words(2, true),
            'subtitle' => null,
            'description' => fake()->paragraph(),
            'group_label' => null,
            'position' => fake()->numberBetween(1, 50) * 10,
            'is_visible' => true,
        ];
    }
}
