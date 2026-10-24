<?php

namespace Database\Factories;

use App\Models\MedicalSpecialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalSpecialty>
 */
class MedicalSpecialtyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'is_active' => true,
        ];
    }
}
