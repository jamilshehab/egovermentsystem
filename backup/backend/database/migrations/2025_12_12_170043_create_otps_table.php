<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('citizen_request_id')->constrained()->onDelete('cascade');
            $table->string('otp_hash');          // hashed OTP
            $table->timestamp('expires_at');
            $table->boolean('is_used')->default(false);
            $table->unsignedInteger('attempts')->default(0);
            $table->string('sent_via')->nullable(); // e.g. azure_graph
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
