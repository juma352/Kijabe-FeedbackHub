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
        Schema::create('learner_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('feedback')->onDelete('cascade');
            $table->decimal('total_score', 5, 2); // 0-100 scale
            $table->string('experience_level', 50); // Exceptional, Excellent, Good, etc.
            $table->decimal('environment_score', 5, 2);
            $table->decimal('content_quality_score', 5, 2);
            $table->decimal('engagement_score', 5, 2);
            $table->decimal('support_system_score', 5, 2);
            $table->json('experience_data'); // Full detailed analysis data
            $table->timestamps();
            
            $table->index(['experience_level']);
            $table->index(['total_score']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_experiences');
    }
};
