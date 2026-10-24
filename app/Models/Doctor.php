<?php

namespace App\Models;

use Database\Factories\DoctorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'uuid',
    'user_id',
    'first_name',
    'last_name',
    'photo_path',
    'consulting_room',
    'extension',
    'is_active',
])]
class Doctor extends Model
{
    /** @use HasFactory<DoctorFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Doctor $doctor): void {
            $doctor->uuid ??= (string) Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(DoctorTranslation::class);
    }

    public function medicalSpecialties(): BelongsToMany
    {
        return $this->belongsToMany(MedicalSpecialty::class)->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
