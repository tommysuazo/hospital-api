<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_verification_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique('avc_uuid_unique');
            $table->string('account_type', 20);
            $table->unsignedBigInteger('account_id');
            $table->string('purpose', 30);
            $table->string('code_hash');
            $table->boolean('remember')->default(false);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('resend_available_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index(['account_type', 'account_id', 'purpose'], 'avc_account_purpose_idx');
            $table->index(['purpose', 'expires_at'], 'avc_purpose_expires_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_verification_codes');
    }
};
