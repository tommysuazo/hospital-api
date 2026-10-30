<?php

namespace App\Models;

use Database\Factories\MedicalAreaTranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'medical_area_id',
    'locale',
    'name',
])]
class MedicalAreaTranslation extends Model
{
    /** @use HasFactory<MedicalAreaTranslationFactory> */
    use HasFactory;

    public function medicalArea(): BelongsTo
    {
        return $this->belongsTo(MedicalArea::class);
    }
}
