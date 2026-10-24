<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_medical_specialty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();
            $table->foreignId('medical_specialty_id')
                ->constrained();
            $table->timestamps();

            $table->unique(
                ['doctor_id', 'medical_specialty_id'],
                'doctor_medical_specialty_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_medical_specialty');
    }
};
