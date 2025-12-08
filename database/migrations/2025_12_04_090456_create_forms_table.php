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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('slug')->unique();
            $table->json('fields'); // Store form field definitions
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('share_token')->unique(); // For public sharing
            $table->json('settings')->nullable(); // Form settings like notifications, etc.
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
