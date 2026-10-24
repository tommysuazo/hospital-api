<?php

namespace Database\Factories;

use App\Models\MedicalProcedure;
use App\Models\MedicalProcedureTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalProcedureTranslation>
 */
class MedicalProcedureTranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_procedure_id' => MedicalProcedure::factory(),
            'locale' => fake()->randomElement(['es', 'en']),
            'name' => fake()->words(2, true),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}
