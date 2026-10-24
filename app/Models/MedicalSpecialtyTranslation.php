<?php

namespace App\Models;

use Database\Factories\MedicalSpecialtyTranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'medical_specialty_id',
    'locale',
    'name',
    'description',
])]
class MedicalSpecialtyTranslation extends Model
{
    /** @use HasFactory<MedicalSpecialtyTranslationFactory> */
    use HasFactory;

    public function medicalSpecialty(): BelongsTo
    {
        return $this->belongsTo(MedicalSpecialty::class);
    }
}
