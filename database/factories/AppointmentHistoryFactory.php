<?php

namespace Database\Factories;

use App\Enums\AppointmentHistoryEventEnum;
use App\Models\Appointment;
use App\Models\AppointmentHistory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<AppointmentHistory>
 */
class AppointmentHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'event' => fake()->randomElement(AppointmentHistoryEventEnum::cases()),
            'actor_type' => null,
            'actor_id' => null,
            'old_values' => fake()->optional()->randomElement([
                ['status' => 'confirmed'],
                ['appointment_date' => '2026-10-25', 'turn_number' => 8],
            ]),
            'new_values' => fake()->optional()->randomElement([
                ['status' => 'cancelled'],
                ['appointment_date' => '2026-10-28', 'turn_number' => 3],
            ]),
            'created_at' => now(),
        ];
    }

    public function forActor(Model $actor): static
    {
        return $this->state(fn () => [
            'actor_type' => $actor->getMorphClass(),
            'actor_id' => $actor->getKey(),
        ]);
    }
}
