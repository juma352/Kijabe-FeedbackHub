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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('feedback')->onDelete('cascade');
            $table->decimal('sentiment_score', 8, 2)->nullable();
            $table->decimal('keyword_score', 8, 2)->nullable();
            $table->decimal('urgency_score', 8, 2)->nullable();
            $table->decimal('total_score', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
