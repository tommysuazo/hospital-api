<?php

namespace Database\Factories;

use App\Models\MedicalProcedure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalProcedure>
 */
class MedicalProcedureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'is_active' => true,
        ];
    }
}
