<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\DoctorUnavailability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorUnavailability>
 */
class DoctorUnavailabilityFactory extends Factory
{
    public function definition(): array
    {
        $startsOn = fake()->dateTimeBetween('+1 day', '+3 months');
        $latestEnd = (clone $startsOn)->modify('+14 days');
        $endsOn = fake()->dateTimeBetween($startsOn, $latestEnd);

        return [
            'doctor_id' => Doctor::factory(),
            'starts_on' => $startsOn->format('Y-m-d'),
            'ends_on' => $endsOn->format('Y-m-d'),
            'reason' => fake()->randomElement([
                null,
                'Vacation',
                'Medical leave',
                'Administrative leave',
                'Special activity',
            ]),
        ];
    }
}
