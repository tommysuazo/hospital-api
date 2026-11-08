<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Support\Str;

class ClientService
{
    public function __construct(private readonly ClientRepository $clientRepository) {}

    public function create(array $data): Client
    {
        return $this->clientRepository->create([
            ...$data,
            'uuid' => (string) Str::uuid(),
        ]);
    }

    public function findActiveByEmail(string $email): ?Client
    {
        return $this->clientRepository->findActiveByEmail($email);
    }

    public function findActiveById(int $id): ?Client
    {
        return $this->clientRepository->findActiveById($id);
    }

    public function markEmailAsVerified(Client $client): void
    {
        $this->clientRepository->markEmailAsVerified($client);
    }

    public function updateLastLogin(Client $client): void
    {
        $this->clientRepository->updateLastLogin($client);
    }

    public function resetPassword(Client $client, string $password): void
    {
        $this->clientRepository->updatePassword($client, $password);
        $this->clientRepository->invalidateAccess($client);
    }
}
