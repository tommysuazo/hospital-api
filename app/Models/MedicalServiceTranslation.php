<?php

namespace App\Models;

use Database\Factories\MedicalServiceTranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'medical_service_id',
    'locale',
    'name',
    'short_description',
    'description',
])]
class MedicalServiceTranslation extends Model
{
    /** @use HasFactory<MedicalServiceTranslationFactory> */
    use HasFactory;

    public function medicalService(): BelongsTo
    {
        return $this->belongsTo(MedicalService::class);
    }
}
