<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Support\Str;

class ClientRepository
{
    public function findActiveByEmail(string $email): ?Client
    {
        return Client::query()
            ->where('email', $email)
            ->where('is_active', true)
            ->first();
    }

    public function findActiveById(int $id): ?Client
    {
        return Client::query()
            ->whereKey($id)
            ->where('is_active', true)
            ->first();
    }

    public function create(array $data): Client
    {
        return Client::query()->create([
            'uuid' => $data['uuid'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'document_type' => $data['document_type'],
            'document_number' => $data['document_number'],
            'birth_date' => $data['birth_date'],
            'gender' => $data['gender'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_active' => true,
        ]);
    }

    public function markEmailAsVerified(Client $client): void
    {
        if ($client->email_verified_at === null) {
            $client->email_verified_at = now();
            $client->save();
        }
    }

    public function updateLastLogin(Client $client): void
    {
        $client->last_login_at = now();
        $client->save();
    }

    public function updatePassword(Client $client, string $password): void
    {
        $client->password = $password;
        $client->save();
    }

    public function invalidateAccess(Client $client): void
    {
        $client->remember_token = Str::random(60);
        $client->save();
        $client->tokens()->delete();
    }
}
