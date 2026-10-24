<?php

namespace Database\Factories;

use App\Enums\LocaleEnum;
use App\Models\Doctor;
use App\Models\DoctorTranslation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorTranslation>
 */
class DoctorTranslationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'locale' => fake()->randomElement(array_column(LocaleEnum::cases(), 'value')),
            'professional_summary' => fake()->paragraph(),
        ];
    }
}
