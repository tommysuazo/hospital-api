<?php

namespace App\Policies;

use App\Models\Client;

class AppointmentPolicy
{
    public function create(Client $client): bool
    {
        return $client->is_active && $client->email_verified_at !== null;
    }
}
