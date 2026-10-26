<?php

use App\Enums\AppointmentHistoryEventEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->constrained();
            $table->enum('event', array_column(AppointmentHistoryEventEnum::cases(), 'value'));
            $table->nullableMorphs('actor');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(
                ['appointment_id', 'created_at'],
                'appointment_histories_appointment_created_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_histories');
    }
};
