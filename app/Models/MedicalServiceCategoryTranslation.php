<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'medical_service_category_id',
    'locale',
    'name',
])]
class MedicalServiceCategoryTranslation extends Model
{
    public function medicalServiceCategory(): BelongsTo
    {
        return $this->belongsTo(MedicalServiceCategory::class);
    }
}
