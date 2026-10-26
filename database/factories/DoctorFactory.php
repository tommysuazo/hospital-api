<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => null,
            'picture' => null,
            'consulting_room' => fake()->bothify('Consultorio ##?'),
            'phone_extension' => fake()->numerify('####'),
            'is_active' => true,
        ];
    }
}
