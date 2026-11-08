<?php

namespace App\Models;

use Database\Factories\MedicalServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'medical_service_category_id',
    'slug',
    'sort_order',
    'is_active',
])]
class MedicalService extends Model
{
    /** @use HasFactory<MedicalServiceFactory> */
    use HasFactory, SoftDeletes;

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            MedicalServiceCategory::class,
            'medical_service_category_id'
        );
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MedicalServiceTranslation::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
