<?php

namespace Database\Factories;

use App\Enums\MedicalResultStatusEnum;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\MedicalResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MedicalResult>
 */
class MedicalResultFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'client_id' => Client::factory(),
            'appointment_id' => null,
            'result_date' => fake()->dateTimeBetween('-1 year')->format('Y-m-d'),
            'status' => MedicalResultStatusEnum::Draft->value,
            'file_path' => null,
            'published_at' => null,
            'created_by' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => MedicalResultStatusEnum::Published->value,
            'published_at' => fake()->dateTimeBetween('-1 year'),
        ]);
    }

    public function forAppointment(Appointment $appointment): static
    {
        return $this->state(fn () => [
            'client_id' => $appointment->client_id,
            'appointment_id' => $appointment->id,
        ]);
    }

    public function createdBy(User $user): static
    {
        return $this->state(fn () => [
            'created_by' => $user->id,
        ]);
    }
}
