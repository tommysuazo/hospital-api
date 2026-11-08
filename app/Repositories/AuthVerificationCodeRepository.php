<?php

namespace App\Repositories;

use App\Models\AuthVerificationCode;

class AuthVerificationCodeRepository
{
    public function create(array $data): AuthVerificationCode
    {
        return AuthVerificationCode::query()->create([
            'uuid' => $data['uuid'],
            'account_type' => $data['account_type'],
            'account_id' => $data['account_id'],
            'purpose' => $data['purpose'],
            'code_hash' => $data['code_hash'],
            'remember' => $data['remember'],
            'attempts' => 0,
            'expires_at' => $data['expires_at'],
            'resend_available_at' => $data['resend_available_at'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
        ]);
    }

    public function findByUuid(string $uuid): ?AuthVerificationCode
    {
        return AuthVerificationCode::query()->where('uuid', $uuid)->first();
    }

    public function invalidateActive(string $accountType, int $accountId, string $purpose): void
    {
        AuthVerificationCode::query()
            ->where('account_type', $accountType)
            ->where('account_id', $accountId)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);
    }

    public function incrementAttempts(AuthVerificationCode $challenge): void
    {
        $challenge->increment('attempts');
        $challenge->refresh();
    }

    public function markVerified(AuthVerificationCode $challenge): void
    {
        $challenge->verified_at = now();
        $challenge->save();
    }

    public function consume(AuthVerificationCode $challenge): void
    {
        $challenge->consumed_at = now();
        $challenge->save();
    }
}
