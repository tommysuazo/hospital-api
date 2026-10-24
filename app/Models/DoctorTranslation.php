<?php

namespace App\Models;

use Database\Factories\DoctorTranslationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'doctor_id',
    'locale',
    'professional_summary',
])]
class DoctorTranslation extends Model
{
    /** @use HasFactory<DoctorTranslationFactory> */
    use HasFactory;

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
