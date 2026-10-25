<?php

namespace App\Models;

use Database\Factories\DoctorUnavailabilityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'doctor_id',
    'starts_on',
    'ends_on',
    'reason',
])]
class DoctorUnavailability extends Model
{
    /** @use HasFactory<DoctorUnavailabilityFactory> */
    use HasFactory, SoftDeletes;

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }
}
