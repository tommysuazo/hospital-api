<?php

use App\Enums\DocumentTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('first_name');
            $table->string('last_name');

            $table->enum('document_type', DocumentTypeEnum::cases());
            $table->string('document_number', 50);

            $table->date('birth_date');
            $table->string('gender', 20);

            $table->string('phone', 30);
            $table->string('email')->unique();

            $table->string('password');

            $table->boolean('is_active')->default(true);

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->rememberToken();

            $table->timestamps();

            $table->unique(
                ['document_type', 'document_number'],
                'clients_document_unique'
            );

            $table->index('phone');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
