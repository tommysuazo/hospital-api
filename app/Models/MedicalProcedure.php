<?php

namespace App\Models;

use Database\Factories\MedicalProcedureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug',
    'is_active',
])]
class MedicalProcedure extends Model
{
    /** @use HasFactory<MedicalProcedureFactory> */
    use HasFactory;

    public function translations(): HasMany
    {
        return $this->hasMany(MedicalProcedureTranslation::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
