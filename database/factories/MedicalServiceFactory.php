<?php

namespace Database\Factories;

use App\Models\MedicalService;
use App\Models\MedicalServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalService>
 */
class MedicalServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_service_category_id' => MedicalServiceCategory::factory(),
            'slug' => fake()->unique()->slug(),
            'sort_order' => fake()->unique()->numberBetween(1, 1000),
            'is_active' => true,
        ];
    }
}
