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
        Schema::table('feedback', function (Blueprint $table) {
            $table->enum('original_sentiment', ['positive', 'neutral', 'negative'])->nullable()->after('sentiment');
            $table->boolean('sentiment_manually_edited')->default(false)->after('original_sentiment');
            $table->json('departments')->nullable()->after('sentiment_manually_edited');
            $table->boolean('action_required')->default(false)->after('departments');
            $table->timestamp('action_taken_at')->nullable()->after('action_required');
            $table->text('action_notes')->nullable()->after('action_taken_at');
            $table->json('notified_emails')->nullable()->after('action_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn([
                'original_sentiment',
                'sentiment_manually_edited', 
                'departments',
                'action_required',
                'action_taken_at',
                'action_notes',
                'notified_emails'
            ]);
        });
    }
};
