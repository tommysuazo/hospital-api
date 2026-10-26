<?php

use App\Enums\MedicalResultStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_results', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('client_id')
                ->constrained()
                ->restrictOnDelete();
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();
            $table->date('result_date');
            $table->enum('status', array_column(MedicalResultStatusEnum::cases(), 'value'))
                ->default(MedicalResultStatusEnum::Draft->value);
            $table->string('file_path')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('appointment_id');
            $table->index('status');
            $table->index('result_date');
            $table->index('published_at');
            $table->index(
                ['client_id', 'status'],
                'medical_results_client_status_index'
            );
            $table->index(
                ['client_id', 'result_date'],
                'medical_results_client_result_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_results');
    }
};
