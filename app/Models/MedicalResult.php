<?php

namespace App\Models;

use App\Enums\MedicalResultStatusEnum;
use Database\Factories\MedicalResultFactory;
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
    'appointment_id',
    'result_date',
    'status',
    'file_path',
    'published_at',
    'created_by',
])]
class MedicalResult extends Model
{
    /** @use HasFactory<MedicalResultFactory> */
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (MedicalResult $medicalResult): void {
            $medicalResult->uuid ??= (string) Str::uuid();
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MedicalResultTranslation::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function casts(): array
    {
        return [
            'result_date' => 'date',
            'status' => MedicalResultStatusEnum::class,
            'published_at' => 'datetime',
        ];
    }
}
