<?php

namespace App\Models;

use Database\Factories\MedicalServiceCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'slug',
    'sort_order',
    'is_active',
])]
class MedicalServiceCategory extends Model
{
    /** @use HasFactory<MedicalServiceCategoryFactory> */
    use HasFactory, SoftDeletes;

    public function medicalServices(): HasMany
    {
        return $this->hasMany(MedicalService::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(MedicalServiceCategoryTranslation::class);
    }

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
