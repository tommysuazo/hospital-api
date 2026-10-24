<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\MedicalSpecialty;
use App\Models\MedicalSpecialtyTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalSpecialtyTranslation>
 */
class MedicalSpecialtyTranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_specialty_id' => MedicalSpecialty::factory(),
            'locale' => fake()->randomElement(array_column(LocaleEnum::cases(), 'value')),
            'name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
        ];
    }
}
