<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    public function findActiveByEmail(string $email): ?User
    {
        return $this->userRepository->findActiveByEmail($email);
    }

    public function findActiveById(int $id): ?User
    {
        return $this->userRepository->findActiveById($id);
    }

    public function markEmailAsVerified(User $user): void
    {
        $this->userRepository->markEmailAsVerified($user);
    }

    public function updateLastLogin(User $user): void
    {
        $this->userRepository->updateLastLogin($user);
    }
}
