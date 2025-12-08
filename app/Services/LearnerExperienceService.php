<?php

namespace App\Services;

use App\Models\Feedback;

class LearnerExperienceService
{
    /**
     * Calculate comprehensive learner experience score based on the four main components
     */
    public function calculateLearnerExperienceScore(Feedback $feedback): array
    {
        $content = strtolower($feedback->content);
        // Ensure metadata is always an array (handle JSON string case)
        $metadata = $feedback->metadata;
        if (is_string($metadata)) {
            $metadata = json_decode($metadata, true) ?? [];
        }
        $metadata = $metadata ?? [];
        
        // 1. Learning Environment Score (25% of total)
        $environmentScore = $this->calculateEnvironmentScore($content, $metadata);
        
        // 2. Content Quality Score (25% of total)
        $contentQualityScore = $this->calculateContentQualityScore($content, $metadata);
        
        // 3. Learner Engagement Score (25% of total)
        $engagementScore = $this->calculateEngagementScore($content, $metadata);
        
        // 4. Support System Score (25% of total)
        $supportSystemScore = $this->calculateSupportSystemScore($content, $metadata);
        
        // Calculate overall learner experience score (0-100)
        $totalScore = ($environmentScore + $contentQualityScore + $engagementScore + $supportSystemScore) / 4;
        
        // Determine experience level
        $experienceLevel = $this->getExperienceLevel($totalScore);
        
        return [
            'total_score' => round($totalScore, 2),
            'experience_level' => $experienceLevel,
            'environment_score' => round($environmentScore, 2),
            'content_quality_score' => round($contentQualityScore, 2),
            'engagement_score' => round($engagementScore, 2),
            'support_system_score' => round($supportSystemScore, 2),
            'recommendations' => $this->generateRecommendations($environmentScore, $contentQualityScore, $engagementScore, $supportSystemScore),
            'priority_areas' => $this->identifyPriorityAreas($environmentScore, $contentQualityScore, $engagementScore, $supportSystemScore)
        ];
    }
    
    /**
     * 1. Learning Environment Score (Physical, Social, Cultural)
     */
    private function calculateEnvironmentScore(string $content, array $metadata): float
    {
        $score = 50; // Base neutral score
        
        // Physical Environment indicators
        $physicalPositive = ['clean', 'comfortable', 'quiet', 'spacious', 'well-equipped', 'good facilities', 'proper lighting', 'adequate space'];
        $physicalNegative = ['noisy', 'crowded', 'dirty', 'uncomfortable', 'broken', 'inadequate', 'poor facilities', 'too hot', 'too cold', 'construction noise', 'cats licking plates', 'monkeys', 'muddy'];
        
        // Social Environment indicators
        $socialPositive = ['friendly', 'supportive', 'collaborative', 'respectful', 'inclusive', 'good teamwork', 'positive atmosphere'];
        $socialNegative = ['rude', 'unfriendly', 'discrimination', 'ignored', 'not listened to', 'poor teamwork', 'unwelcoming', 'treated as children'];
        
        // Cultural Context indicators
        $culturalPositive = ['inclusive', 'diverse', 'respectful', 'understanding', 'accommodating'];
        $culturalNegative = ['discrimination', 'bias', 'unfair treatment', 'cultural insensitivity'];
        
        // Calculate physical environment impact
        foreach ($physicalPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 8;
            }
        }
        foreach ($physicalNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 12;
            }
        }
        
        // Calculate social environment impact
        foreach ($socialPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 10;
            }
        }
        foreach ($socialNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 15;
            }
        }
        
        // Calculate cultural context impact
        foreach ($culturalPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 12;
            }
        }
        foreach ($culturalNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 18;
            }
        }
        
        // Category-specific adjustments
        if (isset($metadata['category_code'])) {
            switch ($metadata['category_code']) {
                case 'ENVR':
                    $score *= 1.2; // Environment feedback gets weighted higher
                    break;
                case 'WELF':
                    $score *= 1.1; // Welfare feedback impacts environment
                    break;
                case 'SECR':
                    $score *= 0.8; // Security issues negatively impact environment
                    break;
            }
        }
        
        return max(0, min(100, $score));
    }
    
    /**
     * 2. Content Quality Score (Relevance, Clarity, Accessibility)
     */
    private function calculateContentQualityScore(string $content, array $metadata): float
    {
        $score = 50; // Base neutral score
        
        // Relevance and Engagement indicators
        $relevancePositive = ['relevant', 'interesting', 'engaging', 'practical', 'applicable', 'useful', 'meaningful'];
        $relevanceNegative = ['irrelevant', 'boring', 'outdated', 'useless', 'theoretical only', 'not applicable'];
        
        // Clarity and Structure indicators
        $clarityPositive = ['clear', 'well-organized', 'structured', 'logical', 'easy to understand', 'well-explained'];
        $clarityNegative = ['confusing', 'unclear', 'disorganized', 'hard to understand', 'poorly explained', 'complicated'];
        
        // Accessibility indicators
        $accessibilityPositive = ['accessible', 'accommodating', 'flexible', 'multiple formats', 'easy access'];
        $accessibilityNegative = ['inaccessible', 'difficult access', 'no accommodations', 'rigid', 'limited access'];
        
        // Calculate relevance impact
        foreach ($relevancePositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 12;
            }
        }
        foreach ($relevanceNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 15;
            }
        }
        
        // Calculate clarity impact
        foreach ($clarityPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 10;
            }
        }
        foreach ($clarityNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 12;
            }
        }
        
        // Calculate accessibility impact
        foreach ($accessibilityPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 8;
            }
        }
        foreach ($accessibilityNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 10;
            }
        }
        
        // Category-specific adjustments
        if (isset($metadata['category_code'])) {
            switch ($metadata['category_code']) {
                case 'CURR':
                    $score *= 1.3; // Curriculum feedback directly impacts content quality
                    break;
                case 'TECH':
                    $score *= 1.2; // Teaching feedback relates to content delivery
                    break;
                case 'CLTE':
                    $score *= 1.1; // Clinical teaching affects content relevance
                    break;
            }
        }
        
        return max(0, min(100, $score));
    }
    
    /**
     * 3. Learner Engagement Score (Motivation, Participation, Feedback)
     */
    private function calculateEngagementScore(string $content, array $metadata): float
    {
        $score = 50; // Base neutral score
        
        // Motivation and Interest indicators
        $motivationPositive = ['motivated', 'interested', 'excited', 'engaged', 'passionate', 'enthusiastic'];
        $motivationNegative = ['unmotivated', 'bored', 'disinterested', 'frustrated', 'disappointed'];
        
        // Active Participation indicators
        $participationPositive = ['participate', 'involved', 'interactive', 'discussion', 'activities', 'projects'];
        $participationNegative = ['passive', 'not involved', 'no participation', 'one-way', 'lecture only'];
        
        // Feedback and Support indicators
        $feedbackPositive = ['good feedback', 'timely response', 'helpful guidance', 'supportive', 'regular updates'];
        $feedbackNegative = ['no feedback', 'delayed feedback', 'poor response', 'not helpful', 'ignored'];
        
        // Calculate motivation impact
        foreach ($motivationPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 15;
            }
        }
        foreach ($motivationNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 18;
            }
        }
        
        // Calculate participation impact
        foreach ($participationPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 12;
            }
        }
        foreach ($participationNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 10;
            }
        }
        
        // Calculate feedback impact
        foreach ($feedbackPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 10;
            }
        }
        foreach ($feedbackNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 15;
            }
        }
        
        // Category-specific adjustments
        if (isset($metadata['category_code'])) {
            switch ($metadata['category_code']) {
                case 'FEDB':
                    $score *= 1.4; // Feedback action directly impacts engagement
                    break;
                case 'COMM':
                    $score *= 1.2; // Communication affects engagement
                    break;
            }
        }
        
        return max(0, min(100, $score));
    }
    
    /**
     * 4. Support System Score (Teacher Quality, Resources, Social Support)
     */
    private function calculateSupportSystemScore(string $content, array $metadata): float
    {
        $score = 50; // Base neutral score
        
        // Teacher Quality indicators
        $teacherPositive = ['good teacher', 'professional', 'knowledgeable', 'helpful instructor', 'supportive tutor', 'excellent teaching'];
        $teacherNegative = ['poor teaching', 'unprofessional', 'rude teacher', 'unhelpful', 'inadequate instruction'];
        
        // Learning Resources indicators
        $resourcesPositive = ['good resources', 'adequate materials', 'technology available', 'library access', 'equipment available'];
        $resourcesNegative = ['lack of resources', 'inadequate materials', 'no equipment', 'limited access', 'outdated resources'];
        
        // Social Support indicators
        $socialSupportPositive = ['peer support', 'mentorship', 'collaboration', 'team work', 'community'];
        $socialSupportNegative = ['isolated', 'no support', 'poor collaboration', 'alone', 'disconnected'];
        
        // Calculate teacher quality impact
        foreach ($teacherPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 15;
            }
        }
        foreach ($teacherNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 20;
            }
        }
        
        // Calculate resources impact
        foreach ($resourcesPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 12;
            }
        }
        foreach ($resourcesNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 15;
            }
        }
        
        // Calculate social support impact
        foreach ($socialSupportPositive as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score += 10;
            }
        }
        foreach ($socialSupportNegative as $indicator) {
            if (strpos($content, $indicator) !== false) {
                $score -= 12;
            }
        }
        
        // Status-based adjustments
        if (isset($metadata['responsible_person']) && !empty($metadata['responsible_person'])) {
            $score += 5; // Having assigned responsibility improves support
        }
        
        if (isset($metadata['status'])) {
            switch (strtolower($metadata['status'])) {
                case 'done':
                    $score += 15; // Resolved issues show good support
                    break;
                case 'in progress':
                    $score += 8; // Active resolution shows support
                    break;
            }
        }
        
        return max(0, min(100, $score));
    }
    
    /**
     * Determine experience level based on total score
     */
    private function getExperienceLevel(float $score): string
    {
        if ($score >= 85) return 'Exceptional';
        if ($score >= 75) return 'Excellent';
        if ($score >= 65) return 'Good';
        if ($score >= 50) return 'Satisfactory';
        if ($score >= 35) return 'Needs Improvement';
        return 'Critical';
    }
    
    /**
     * Generate recommendations based on component scores
     */
    private function generateRecommendations(float $env, float $content, float $engagement, float $support): array
    {
        $recommendations = [];
        
        if ($env < 50) {
            $recommendations[] = 'Improve physical learning environment (facilities, cleanliness, noise levels)';
            $recommendations[] = 'Foster a more inclusive and respectful social environment';
        }
        
        if ($content < 50) {
            $recommendations[] = 'Enhance content relevance and practical applicability';
            $recommendations[] = 'Improve clarity and structure of learning materials';
        }
        
        if ($engagement < 50) {
            $recommendations[] = 'Increase opportunities for active learner participation';
            $recommendations[] = 'Provide more timely and constructive feedback';
        }
        
        if ($support < 50) {
            $recommendations[] = 'Enhance teacher training and professional development';
            $recommendations[] = 'Improve access to learning resources and technology';
        }
        
        return $recommendations;
    }
    
    /**
     * Identify priority areas for improvement
     */
    private function identifyPriorityAreas(float $env, float $content, float $engagement, float $support): array
    {
        $areas = [
            'Environment' => $env,
            'Content Quality' => $content,
            'Learner Engagement' => $engagement,
            'Support System' => $support
        ];
        
        // Sort by lowest scores (highest priority)
        asort($areas);
        
        return array_keys(array_slice($areas, 0, 2)); // Return top 2 priority areas
    }
    

    
    /**
     * Calculate improvement trend over time
     */
    private function getImprovementTrend($feedbacks): array
    {
        $monthlyScores = [];
        
        foreach ($feedbacks as $feedback) {
            if ($feedback->learnerExperience) {
                $month = $feedback->created_at->format('Y-m');
                $experience = json_decode($feedback->learnerExperience->experience_data, true);
                
                if ($experience && isset($experience['total_score'])) {
                    if (!isset($monthlyScores[$month])) {
                        $monthlyScores[$month] = [];
                    }
                    $monthlyScores[$month][] = $experience['total_score'];
                }
            }
        }
        
        $trend = [];
        foreach ($monthlyScores as $month => $scores) {
            $trend[$month] = round(array_sum($scores) / count($scores), 2);
        }
        
        ksort($trend);
        return $trend;
    }
    
    /**
     * Get comprehensive experience insights for analytics
     */
    public function getExperienceInsights(): array
    {
        $totalAnalyzed = \App\Models\LearnerExperience::count();
        
        if ($totalAnalyzed == 0) {
            return [
                'total_analyzed' => 0,
                'average_scores' => [
                    'environment' => 0,
                    'content_quality' => 0,
                    'engagement' => 0,
                    'support_system' => 0,
                    'total' => 0,
                ],
                'experience_levels' => [],
                'priority_areas' => [],
            ];
        }

        // Average scores for each component
        $averageScores = [
            'environment' => round(\App\Models\LearnerExperience::avg('environment_score') ?? 0, 1),
            'content_quality' => round(\App\Models\LearnerExperience::avg('content_quality_score') ?? 0, 1),
            'engagement' => round(\App\Models\LearnerExperience::avg('engagement_score') ?? 0, 1),
            'support_system' => round(\App\Models\LearnerExperience::avg('support_system_score') ?? 0, 1),
            'total' => round(\App\Models\LearnerExperience::avg('total_score') ?? 0, 1),
        ];

        // Experience level distribution
        $experienceLevels = \App\Models\LearnerExperience::selectRaw('experience_level, COUNT(*) as count')
            ->groupBy('experience_level')
            ->pluck('count', 'experience_level')
            ->toArray();

        // Ensure all levels are represented
        $allLevels = ['Exceptional', 'Excellent', 'Good', 'Satisfactory', 'Needs Improvement', 'Critical'];
        foreach ($allLevels as $level) {
            if (!isset($experienceLevels[$level])) {
                $experienceLevels[$level] = 0;
            }
        }

        // Priority areas analysis
        $priorityAreas = [];
        $experiences = \App\Models\LearnerExperience::whereNotNull('experience_data')->get();
        foreach ($experiences as $experience) {
            $data = is_string($experience->experience_data) 
                ? json_decode($experience->experience_data, true) 
                : $experience->experience_data;
                
            if (isset($data['priority_areas']) && is_array($data['priority_areas'])) {
                foreach ($data['priority_areas'] as $area) {
                    $priorityAreas[$area] = ($priorityAreas[$area] ?? 0) + 1;
                }
            }
        }
        arsort($priorityAreas);

        return [
            'total_analyzed' => $totalAnalyzed,
            'average_scores' => $averageScores,
            'experience_levels' => $experienceLevels,
            'priority_areas' => $priorityAreas,
        ];
    }
}