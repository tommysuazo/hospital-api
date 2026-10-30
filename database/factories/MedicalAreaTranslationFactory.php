<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\MedicalArea;
use App\Models\MedicalAreaTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalAreaTranslation>
 */
class MedicalAreaTranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_area_id' => MedicalArea::factory(),
            'locale' => fake()->randomElement(array_column(LocaleEnum::cases(), 'value')),
            'name' => fake()->words(2, true),
        ];
    }
}
