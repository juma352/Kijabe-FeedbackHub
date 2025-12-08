<?php

namespace App\Services;

use App\Models\Feedback;
use Illuminate\Support\Facades\Cache;

class FeedbackStatsService
{
    const CACHE_KEY = 'feedback_management_stats';
    const CACHE_MINUTES = 5;

    /**
     * Get cached feedback statistics
     */
    public static function getStats($fresh = false)
    {
        if ($fresh) {
            Cache::forget(self::CACHE_KEY);
        }

        return Cache::remember(self::CACHE_KEY, now()->addMinutes(self::CACHE_MINUTES), function () {
            return [
                'total' => Feedback::count(),
                'action_required' => Feedback::where('action_required', true)->count(),
                'pending_actions' => Feedback::where('action_required', true)
                    ->whereNull('action_taken_at')->count(),
                'negative_sentiment' => Feedback::where('sentiment', 'negative')->count(),
                'manually_edited' => Feedback::where('sentiment_manually_edited', true)->count(),
            ];
        });
    }

    /**
     * Invalidate stats cache
     */
    public static function invalidate()
    {
        Cache::forget(self::CACHE_KEY);
    }
}
