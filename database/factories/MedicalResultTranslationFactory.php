<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\MedicalResult;
use App\Models\MedicalResultTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalResultTranslation>
 */
class MedicalResultTranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'medical_result_id' => MedicalResult::factory(),
            'locale' => fake()->randomElement(array_column(LocaleEnum::cases(), 'value')),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
