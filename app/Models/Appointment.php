<?php

namespace App\Models;

use App\Enums\AppointmentForEnum;
use App\Enums\AppointmentStatusEnum;
use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'uuid',
    'client_id',
    'doctor_id',
    'specialty_id',
    'appointment_for',
    'appointment_date',
    'turn_number',
    'reason',
    'status',
])]
class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (Appointment $appointment): void {
            $appointment->uuid ??= (string) Str::uuid();
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(MedicalSpecialty::class, 'specialty_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(AppointmentHistory::class);
    }

    public function medicalResults(): HasMany
    {
        return $this->hasMany(MedicalResult::class);
    }

    protected function casts(): array
    {
        return [
            'appointment_for' => AppointmentForEnum::class,
            'appointment_date' => 'date',
            'turn_number' => 'integer',
            'status' => AppointmentStatusEnum::class,
        ];
    }
}
