<?php

namespace Database\Factories;

use App\Enums\AppointmentForEnum;
use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\MedicalSpecialty;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'client_id' => Client::factory(),
            'doctor_id' => Doctor::factory(),
            'specialty_id' => function (array $attributes): int {
                $doctor = Doctor::query()->findOrFail($attributes['doctor_id']);
                $specialty = MedicalSpecialty::factory()->create();

                $doctor->medicalSpecialties()->syncWithoutDetaching($specialty);

                return $specialty->id;
            },
            'appointment_for' => fake()->randomElement(array_column(AppointmentForEnum::cases(), 'value')),
            'appointment_date' => fake()->dateTimeBetween('+1 day', '+3 months')
                ->format('Y-m-d'),
            'turn_number' => fake()->numberBetween(1, 30),
            'reason' => fake()->optional()->sentence(),
            'status' => AppointmentStatusEnum::Confirmed->value,
        ];
    }
}
