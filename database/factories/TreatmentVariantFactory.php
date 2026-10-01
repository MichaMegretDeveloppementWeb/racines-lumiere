<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Treatment;
use App\Models\TreatmentVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TreatmentVariant>
 */
class TreatmentVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'treatment_id' => Treatment::factory(),
            'label' => null,
            'total_duration_minutes' => fake()->numberBetween(2, 12) * 15,
            'care_duration_minutes' => null,
            'price_cents' => fake()->numberBetween(10, 300) * 100,
            'position' => fake()->unique()->numberBetween(1, 60000),
            'is_visible' => true,
        ];
    }
}
