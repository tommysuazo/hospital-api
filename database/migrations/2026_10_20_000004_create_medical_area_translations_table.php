<?php

use App\Enums\LocaleEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_area_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_area_id')
                ->constrained();
            $table->enum('locale', array_column(LocaleEnum::cases(), 'value'));
            $table->string('name');
            $table->timestamps();

            $table->unique(
                ['medical_area_id', 'locale'],
                'medical_area_locale_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_area_translations');
    }
};
