<?php

namespace App\Services;

use App\Models\Feedback;

class FeedbackAnalysisService
{
    /**
     * Analyze sentiment of feedback content
     */
    public function analyzeSentiment(string $content): array
    {
        // Simple sentiment analysis - in production, integrate with Google NLP, Azure Text Analytics, or OpenAI
        $positiveWords = [
            'excellent', 'amazing', 'fantastic', 'great', 'wonderful', 'awesome', 'perfect', 
            'outstanding', 'brilliant', 'superb', 'exceptional', 'magnificent', 'marvelous',
            'love', 'like', 'happy', 'satisfied', 'pleased', 'delighted', 'impressed',
            'recommend', 'helpful', 'friendly', 'professional', 'quick', 'fast', 'easy'
        ];
        
        $negativeWords = [
            'terrible', 'awful', 'horrible', 'bad', 'worst', 'hate', 'disappointed',
            'frustrated', 'angry', 'annoyed', 'slow', 'difficult', 'confusing',
            'broken', 'failed', 'error', 'problem', 'issue', 'complaint', 'unacceptable',
            'poor', 'unsatisfied', 'unhappy', 'rude', 'unprofessional'
        ];
        
        $urgentWords = [
            'urgent', 'immediately', 'asap', 'emergency', 'critical', 'important',
            'deadline', 'priority', 'escalate', 'manager', 'supervisor', 'complaint',
            'refund', 'cancel', 'legal', 'lawsuit'
        ];
        
        $content = strtolower($content);
        $words = str_word_count($content, 1);
        
        $positiveCount = 0;
        $negativeCount = 0;
        $urgentCount = 0;
        $foundKeywords = [];
        
        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveCount++;
                $foundKeywords[] = $word;
            } elseif (in_array($word, $negativeWords)) {
                $negativeCount++;
                $foundKeywords[] = $word;
            }
            
            if (in_array($word, $urgentWords)) {
                $urgentCount++;
                if (!in_array($word, $foundKeywords)) {
                    $foundKeywords[] = $word;
                }
            }
        }
        
        // Determine sentiment
        if ($positiveCount > $negativeCount) {
            $sentiment = 'positive';
        } elseif ($negativeCount > $positiveCount) {
            $sentiment = 'negative';
        } else {
            $sentiment = 'neutral';
        }
        
        // Calculate confidence (simple approach)
        $totalSentimentWords = $positiveCount + $negativeCount;
        $confidence = $totalSentimentWords > 0 ? max($positiveCount, $negativeCount) / $totalSentimentWords : 0;
        
        return [
            'sentiment' => $sentiment,
            'confidence' => round($confidence, 2),
            'positive_score' => $positiveCount,
            'negative_score' => $negativeCount,
            'urgency_level' => $urgentCount,
            'keywords' => array_unique($foundKeywords)
        ];
    }
    
    /**
     * Calculate comprehensive feedback score
     */
    public function calculateScore(Feedback $feedback): array
    {
        $analysis = $this->analyzeSentiment($feedback->content);
        
        // Sentiment score (-10 to +10)
        $sentimentScore = match($analysis['sentiment']) {
            'positive' => 5 + ($analysis['positive_score'] * 1.5),
            'negative' => -5 - ($analysis['negative_score'] * 1.5),
            default => 0
        };
        
        // Rating influence (-5 to +5)
        $ratingScore = $feedback->rating ? ($feedback->rating - 3) * 2 : 0;
        
        // Urgency penalty/bonus
        $urgencyScore = $analysis['urgency_level'] * -2; // Urgent = lower score (needs attention)
        
        // Length factor (very short or very long feedback might be less reliable)
        $contentLength = strlen($feedback->content);
        $lengthFactor = 1;
        if ($contentLength < 20) {
            $lengthFactor = 0.7; // Short feedback less reliable
        } elseif ($contentLength > 500) {
            $lengthFactor = 1.2; // Detailed feedback more valuable
        }
        
        $totalScore = ($sentimentScore + $ratingScore + $urgencyScore) * $lengthFactor;
        
        // Priority level based on score and urgency
        $priority = 'low';
        if ($analysis['urgency_level'] > 0 || $totalScore < -5) {
            $priority = 'high';
        } elseif ($totalScore < 0) {
            $priority = 'medium';
        }
        
        return [
            'sentiment_score' => round($sentimentScore, 2),
            'rating_score' => $ratingScore,
            'urgency_score' => $urgencyScore,
            'total_score' => round($totalScore, 2),
            'priority' => $priority,
            'confidence' => $analysis['confidence'],
            'keywords' => implode(',', $analysis['keywords'])
        ];
    }
    
    /**
     * Get feedback insights for dashboard
     */
    public function getInsights()
    {
        $feedbacks = Feedback::with('score')->get();
        
        $insights = [
            'total_feedbacks' => $feedbacks->count(),
            'avg_rating' => $feedbacks->whereNotNull('rating')->avg('rating'),
            'sentiment_distribution' => [
                'positive' => $feedbacks->where('sentiment', 'positive')->count(),
                'negative' => $feedbacks->where('sentiment', 'negative')->count(),
                'neutral' => $feedbacks->where('sentiment', 'neutral')->count(),
            ],
            'priority_distribution' => [],
            'source_breakdown' => $feedbacks->groupBy('source')->map->count(),
            'recent_trend' => $this->getRecentTrend(),
            'top_keywords' => $this->getTopKeywords($feedbacks),
            'high_priority_count' => 0,
            'avg_score' => 0
        ];
        
        // Calculate priority and score averages
        if ($feedbacks->count() > 0) {
            $scores = $feedbacks->pluck('score')->filter();
            if ($scores->count() > 0) {
                $insights['avg_score'] = round($scores->avg('total_score'), 2);
                
                // Count high priority items (assuming we add priority to score model)
                $insights['high_priority_count'] = $feedbacks->filter(function($feedback) {
                    return $feedback->score && $feedback->score->total_score < -5;
                })->count();
            }
        }
        
        return $insights;
    }
    
    private function getRecentTrend()
    {
        $last7Days = Feedback::where('created_at', '>=', now()->subDays(7))->get();
        $previous7Days = Feedback::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])->get();
        
        $currentAvg = $last7Days->whereNotNull('rating')->avg('rating') ?? 0;
        $previousAvg = $previous7Days->whereNotNull('rating')->avg('rating') ?? 0;
        
        $trend = 'stable';
        if ($currentAvg > $previousAvg + 0.2) {
            $trend = 'improving';
        } elseif ($currentAvg < $previousAvg - 0.2) {
            $trend = 'declining';
        }
        
        return [
            'trend' => $trend,
            'current_avg' => round($currentAvg, 1),
            'previous_avg' => round($previousAvg, 1),
            'change' => round($currentAvg - $previousAvg, 1)
        ];
    }
    
    private function getTopKeywords($feedbacks)
    {
        $allKeywords = $feedbacks->pluck('keyword')->filter()->flatMap(function($keywords) {
            return explode(',', $keywords);
        })->map('trim')->filter();
        
        $keywordCounts = $allKeywords->countBy();
        
        return $keywordCounts->sortDesc()->take(10)->toArray();
    }
}