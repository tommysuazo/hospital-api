<?php

use App\Enums\AppointmentForEnum;
use App\Enums\AppointmentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('client_id')
                ->constrained();
            $table->foreignId('doctor_id')
                ->constrained();
            $table->foreignId('medical_area_id')
                ->constrained();
            $table->enum('appointment_for', array_column(AppointmentForEnum::cases(), 'value'));
            $table->date('appointment_date');
            $table->unsignedInteger('turn_number');
            $table->text('reason')->nullable();
            $table->enum('status', array_column(AppointmentStatusEnum::cases(), 'value'))
                ->default(AppointmentStatusEnum::Confirmed->value);
            $table->timestamps();
            $table->softDeletes();

            $table->index(
                ['doctor_id', 'appointment_date'],
                'appointments_doctor_date_index'
            );
            $table->index(
                ['client_id', 'appointment_date'],
                'appointments_client_date_index'
            );
            $table->index('status');
            $table->unique(
                ['doctor_id', 'appointment_date', 'turn_number'],
                'appointments_doctor_date_turn_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
