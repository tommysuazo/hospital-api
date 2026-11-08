
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('medical_service_category_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medical_service_category_id');
            $table->foreign(
                'medical_service_category_id',
                'msc_trans_category_fk'
            )->references('id')->on('medical_service_categories');

            $table->string('locale', 5);
            $table->string('name');
            $table->timestamps();

            $table->unique([
                'medical_service_category_id',
                'locale',
            ], 'msc_trans_locale_uq');
        });

        Schema::create('medical_services', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medical_service_category_id');
            $table->foreign(
                'medical_service_category_id',
                'ms_category_fk'
            )->references('id')->on('medical_service_categories');

            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('medical_service_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medical_service_id');
            $table->foreign(
                'medical_service_id',
                'mst_service_fk'
            )->references('id')->on('medical_services');

            $table->string('locale', 5);
            $table->string('name');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();

            $table->unique([
                'medical_service_id',
                'locale',
            ], 'mst_service_locale_uq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_service_translations');
        Schema::dropIfExists('medical_services');
        Schema::dropIfExists('medical_service_category_translations');
        Schema::dropIfExists('medical_service_categories');
    }
};
