<?php

namespace App\Models;

use Database\Factories\MedicalResultDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'uuid',
    'medical_result_id',
    'name',
    'file_path',
    'mime_type',
    'file_size',
])]
class MedicalResultDocument extends Model
{
    /** @use HasFactory<MedicalResultDocumentFactory> */
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::creating(function (MedicalResultDocument $document): void {
            $document->uuid ??= (string) Str::uuid();
        });
    }

    public function medicalResult(): BelongsTo
    {
        return $this->belongsTo(MedicalResult::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }
}
