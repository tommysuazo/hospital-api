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
    private const MALE_PICTURES = [
        '/images/doctors/doctor-01.jpg',
        '/images/doctors/doctor-02.jpg',
    ];

    private const FEMALE_PICTURES = [
        '/images/doctors/doctor-03.jpg',
        '/images/doctors/doctor-04.jpg',
    ];

    public function configure(): static
    {
        return $this->afterMaking(function (Doctor $doctor): void {
            if ($doctor->picture !== null) {
                return;
            }

            $pictures = match ($doctor->user?->gender) {
                'male' => self::MALE_PICTURES,
                'female' => self::FEMALE_PICTURES,
                default => [...self::MALE_PICTURES, ...self::FEMALE_PICTURES],
            };

            $doctor->picture = fake()->randomElement($pictures);
        });
    }

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'user_id' => null,
            'picture' => null,
            'consulting_room' => fake()->bothify('##'),
            'phone_extension' => fake()->numerify('####'),
            'is_active' => true,
        ];
    }
}
