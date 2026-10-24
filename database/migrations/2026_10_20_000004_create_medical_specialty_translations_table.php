<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_specialty_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_specialty_id')
                ->constrained();
            $table->string('locale');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(
                ['medical_specialty_id', 'locale'],
                'medical_specialty_locale_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_specialty_translations');
    }
};
