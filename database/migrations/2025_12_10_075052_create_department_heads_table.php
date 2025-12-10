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
        Schema::create('department_heads', function (Blueprint $table) {
            $table->id();
            $table->string('department_key')->unique(); // e.g., 'gme', 'quality_assurance'
            $table->string('department_name'); // e.g., 'GME', 'Quality Assurance'
            $table->string('head_name');
            $table->string('head_email');
            $table->string('cc_emails')->nullable(); // Comma-separated additional emails
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_heads');
    }
};
