<?php

namespace App\Models;

use Database\Factories\MedicalSpecialtyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'slug',
    'is_active',
])]
class MedicalSpecialty extends Model
{
    /** @use HasFactory<MedicalSpecialtyFactory> */
    use HasFactory, SoftDeletes;

    public function translations(): HasMany
    {
        return $this->hasMany(MedicalSpecialtyTranslation::class);
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class)->withTimestamps();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'specialty_id');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
