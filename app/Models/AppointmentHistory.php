<?php

namespace App\Models;

use App\Enums\AppointmentHistoryEventEnum;
use Database\Factories\AppointmentHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LogicException;

#[Fillable([
    'appointment_id',
    'event',
    'actor_type',
    'actor_id',
    'old_values',
    'new_values',
])]
class AppointmentHistory extends Model
{
    /** @use HasFactory<AppointmentHistoryFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    protected static function booted(): void
    {
        static::updating(function (): never {
            throw new LogicException('Appointment history records are immutable.');
        });

        static::deleting(function (): never {
            throw new LogicException('Appointment history records are permanent.');
        });
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function actor(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'event' => AppointmentHistoryEventEnum::class,
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }
}
