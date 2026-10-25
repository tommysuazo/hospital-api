<?php

namespace Database\Factories;

use App\Enums\DayOfWeekEnum;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorSchedule>
 */
class DoctorScheduleFactory extends Factory
{
    public function definition(): array
    {
        [$startTime, $endTime] = fake()->randomElement([
            ['08:00:00', '12:00:00'],
            ['14:00:00', '18:00:00'],
        ]);

        return [
            'doctor_id' => Doctor::factory(),
            'day_of_week' => fake()->randomElement(DayOfWeekEnum::cases()),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'is_active' => true,
        ];
    }
}
