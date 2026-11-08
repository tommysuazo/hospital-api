<?php

namespace Database\Factories;

use App\Models\MedicalServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalServiceCategory>
 */
class MedicalServiceCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'sort_order' => fake()->unique()->numberBetween(1, 1000),
            'is_active' => true,
        ];
    }
}
