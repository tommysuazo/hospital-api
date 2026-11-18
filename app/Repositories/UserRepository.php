<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function findActiveByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->where('is_active', true)
            ->first();
    }

    public function findActiveById(int $id): ?User
    {
        return User::query()
            ->whereKey($id)
            ->where('is_active', true)
            ->first();
    }

    public function updateLastLogin(User $user): void
    {
        $user->last_login_at = now();
        $user->save();
    }
}
