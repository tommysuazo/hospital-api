<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'uuid',
    'account_type',
    'account_id',
    'purpose',
    'code_hash',
    'remember',
    'attempts',
    'expires_at',
    'resend_available_at',
    'verified_at',
    'consumed_at',
    'ip_address',
    'user_agent',
])]
#[Hidden(['code_hash'])]
class AuthVerificationCode extends Model
{
    protected function casts(): array
    {
        return [
            'account_id' => 'integer',
            'remember' => 'boolean',
            'attempts' => 'integer',
            'expires_at' => 'datetime',
            'resend_available_at' => 'datetime',
            'verified_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }
}
