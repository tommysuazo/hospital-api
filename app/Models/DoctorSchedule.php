<?php

namespace App\Models;

use App\Enums\DayOfWeekEnum;
use Database\Factories\DoctorScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'doctor_id',
    'day_of_week',
    'start_time',
    'end_time',
    'is_active',
])]
class DoctorSchedule extends Model
{
    /** @use HasFactory<DoctorScheduleFactory> */
    use HasFactory, SoftDeletes;

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    protected function casts(): array
    {
        return [
            'day_of_week' => DayOfWeekEnum::class,
            'is_active' => 'boolean',
        ];
    }
}
