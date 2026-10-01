<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
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
            'name' => fake()->name(),
            'organization_name' => null,
            'specialty' => fake()->jobTitle(),
            'town' => fake()->city(),
            'website_url' => fake()->url(),
            'position' => fake()->numberBetween(1, 50) * 10,
            'is_visible' => false,
        ];
    }
}
