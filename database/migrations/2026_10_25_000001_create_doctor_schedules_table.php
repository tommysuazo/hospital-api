<?php

use App\Enums\DayOfWeekEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('day_of_week', array_column(DayOfWeekEnum::cases(), 'value'));
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['doctor_id', 'day_of_week', 'start_time', 'end_time'],
                'doctor_schedules_doctor_day_times_unique'
            );
            $table->index(
                ['doctor_id', 'day_of_week', 'is_active'],
                'doctor_schedules_lookup_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
