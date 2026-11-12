<?php

namespace App\Policies;

use App\Enums\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\Client;

class AppointmentPolicy
{
    public function viewAny(Client $client): bool
    {
        return $client->is_active;
    }

    public function create(Client $client): bool
    {
        return $client->is_active && $client->email_verified_at !== null;
    }

    public function update(Client $client, Appointment $appointment): bool
    {
        return $client->is_active
            && $client->email_verified_at !== null
            && $appointment->client_id === $client->id
            && $appointment->status === AppointmentStatusEnum::Confirmed->value;
    }
}
