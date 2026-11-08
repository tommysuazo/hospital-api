<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\MedicalService;
use App\Models\MedicalServiceTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalServiceTranslation>
 */
class MedicalServiceTranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_service_id' => MedicalService::factory(),
            'locale' => fake()->randomElement(array_column(LocaleEnum::cases(), 'value')),
            'name' => fake()->words(2, true),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}
