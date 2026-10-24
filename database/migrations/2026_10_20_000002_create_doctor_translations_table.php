<?php

use App\Enums\LocaleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained();
            $table->enum('locale', array_column(LocaleEnum::cases(), 'value'));
            $table->text('professional_summary')->nullable();
            $table->timestamps();

            $table->unique(['doctor_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_translations');
    }
};
