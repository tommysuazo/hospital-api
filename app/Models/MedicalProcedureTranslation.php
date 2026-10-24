<?php

namespace App\Models;

use Database\Factories\MedicalProcedureTranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'medical_procedure_id',
    'locale',
    'name',
    'short_description',
    'description',
])]
class MedicalProcedureTranslation extends Model
{
    /** @use HasFactory<MedicalProcedureTranslationFactory> */
    use HasFactory;

    public function medicalProcedure(): BelongsTo
    {
        return $this->belongsTo(MedicalProcedure::class);
    }
}
