<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
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
            'locale' => fake()->randomElement(array_column(LocaleEnum::cases(), 'value')),
            'name' => fake()->words(2, true),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}
