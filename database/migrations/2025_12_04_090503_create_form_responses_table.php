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
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained()->onDelete('cascade');
            $table->json('responses'); // Store all form field responses
            $table->string('respondent_email')->nullable();
            $table->string('respondent_name')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_responses');
    }
};
