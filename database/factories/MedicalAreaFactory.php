<?php

namespace Database\Factories;

use App\Models\MedicalArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalArea>
 */
class MedicalAreaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(),
            'is_active' => true,
        ];
    }
}
