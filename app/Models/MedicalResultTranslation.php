<?php

namespace App\Models;

use Database\Factories\MedicalResultTranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'medical_result_id',
    'locale',
    'title',
    'description',
])]
class MedicalResultTranslation extends Model
{
    /** @use HasFactory<MedicalResultTranslationFactory> */
    use HasFactory;

    public function medicalResult(): BelongsTo
    {
        return $this->belongsTo(MedicalResult::class);
    }
}
