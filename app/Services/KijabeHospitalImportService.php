<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\LearnerExperience;
use App\Services\FeedbackAnalysisService;
use App\Services\LearnerExperienceService;
use Illuminate\Support\Facades\Log;

class KijabeHospitalImportService
{
    private $categoryMap = [
        'CURR' => 'Curriculum',
        'FEDB' => 'Feedback Action', 
        'COMM' => 'Communication',
        'SECR' => 'Security',
        'POCY' => 'Policy',
        'WELF' => 'Welfare',
        'TECH' => 'Teaching',
        'CLTE' => 'Clinical Teaching',
        'ENVR' => 'Environment',
        'FEES' => 'Fees',
        'CURR-O' => 'Curriculum Objectives'
    ];

    private $sourceMap = [
        'KCHS CS' => 'KCHS Clinical Students',
        'KCMS/COC' => 'KCMS/COC',
        'communication' => 'Communication Feedback',
        'WELF' => 'Welfare Feedback'
    ];

    public function importKijabeCSV($filePath)
    {
        try {
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                throw new \Exception("Could not open file: {$filePath}");
            }

            $count = 0;
            $analysisService = new FeedbackAnalysisService();
            
            // Skip the header and metadata rows
            for ($i = 0; $i < 4; $i++) {
                fgetcsv($handle);
            }

            while (($row = fgetcsv($handle)) !== false) {
                // Skip empty rows or rows without feedback content
                if (empty($row[2]) || empty(trim($row[2]))) {
                    continue;
                }

                $dateString = $row[0] ?? '';
                $serialNo = $row[1] ?? '';
                $identifiedGap = trim($row[2]) ?? '';
                $source = $row[3] ?? '';
                $code = $row[4] ?? '';
                $entryDate = $row[5] ?? '';
                $response = $row[6] ?? '';
                $action = $row[7] ?? '';
                $responsiblePerson = $row[8] ?? '';
                $status = $row[11] ?? '';
                $dateClosed = $row[12] ?? '';
                $remarks = $row[15] ?? '';
                $taskAllocation = $row[16] ?? '';

                // Skip if no meaningful content
                if (empty($identifiedGap)) {
                    continue;
                }

                // Map source to readable format
                $mappedSource = $this->sourceMap[$source] ?? $source;
                if (empty($mappedSource)) {
                    $mappedSource = 'Kijabe Hospital Education Department';
                }

                // Map category code to description
                $category = $this->categoryMap[$code] ?? $code;

                // Create metadata
                $metadata = [
                    'hospital_department' => 'Education',
                    'serial_number' => $serialNo,
                    'original_date' => $dateString,
                    'entry_date' => $entryDate,
                    'category_code' => $code,
                    'category' => $category,
                    'response_provided' => !empty($response),
                    'action_taken' => $action,
                    'responsible_person' => $responsiblePerson,
                    'status' => $status,
                    'date_closed' => $dateClosed,
                    'remarks' => $remarks,
                    'task_allocation' => $taskAllocation,
                    'imported_from' => 'Kijabe Hospital CSV'
                ];

                // Determine urgency based on content and status
                $urgencyLevel = $this->determineUrgency($identifiedGap, $status, $action);
                
                // Create feedback record
                $feedback = Feedback::create([
                    'source' => $mappedSource,
                    'content' => $identifiedGap,
                    'metadata' => json_encode($metadata),
                    'rating' => $this->estimateRating($identifiedGap, $status, $response)
                ]);

                // Analyze the feedback
                $scoreData = $analysisService->calculateScore($feedback);
                
                // Enhance with hospital-specific analysis
                $hospitalAnalysis = $this->analyzeHospitalFeedback($identifiedGap, $code, $status);
                
                // Update feedback with analysis
                $feedback->update([
                    'sentiment' => $hospitalAnalysis['sentiment'],
                    'keyword' => $hospitalAnalysis['keywords']
                ]);

                // Create score record
                $feedback->score()->create([
                    'sentiment_score' => $hospitalAnalysis['sentiment_score'],
                    'keyword_score' => $hospitalAnalysis['category_score'],
                    'urgency_score' => $hospitalAnalysis['urgency_score'], 
                    'total_score' => $hospitalAnalysis['total_score']
                ]);
                
                // Calculate learner experience score
                $learnerExperienceService = new LearnerExperienceService();
                $experienceData = $learnerExperienceService->calculateLearnerExperienceScore($feedback);
                
                // Create learner experience record
                LearnerExperience::create([
                    'feedback_id' => $feedback->id,
                    'total_score' => $experienceData['total_score'],
                    'experience_level' => $experienceData['experience_level'],
                    'environment_score' => $experienceData['environment_score'],
                    'content_quality_score' => $experienceData['content_quality_score'],
                    'engagement_score' => $experienceData['engagement_score'],
                    'support_system_score' => $experienceData['support_system_score'],
                    'experience_data' => json_encode($experienceData)
                ]);

                $count++;
            }

            fclose($handle);

            return [
                'success' => true,
                'count' => $count,
                'message' => "Successfully imported {$count} Kijabe Hospital feedback entries"
            ];

        } catch (\Exception $e) {
            Log::error('Kijabe Hospital CSV import failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function determineUrgency($content, $status, $action)
    {
        $content = strtolower($content);
        $urgentWords = [
            'urgent', 'immediately', 'critical', 'dangerous', 'emergency',
            'unsafe', 'expired', 'broken', 'failed', 'not working',
            'poor quality', 'unhygienic', 'dirty', 'contaminated',
            'discrimination', 'unfair', 'rude', 'harassment'
        ];

        $urgencyScore = 0;
        foreach ($urgentWords as $word) {
            if (strpos($content, $word) !== false) {
                $urgencyScore += 2;
            }
        }

        // Status-based urgency
        if (strtolower($status) === 'in progress') {
            $urgencyScore += 1;
        } elseif (strtolower($status) === 'done') {
            $urgencyScore -= 1;
        }

        return min($urgencyScore, 10); // Cap at 10
    }

    private function estimateRating($content, $status, $response)
    {
        $content = strtolower($content);
        
        // Very negative indicators
        $veryNegative = ['terrible', 'awful', 'disgusting', 'unacceptable', 'horrible', 'worst'];
        $negative = ['poor', 'bad', 'disappointed', 'frustrated', 'unfair', 'dirty'];
        $neutral = ['okay', 'average', 'fine'];
        $positive = ['good', 'satisfied', 'happy', 'pleased'];
        $veryPositive = ['excellent', 'amazing', 'outstanding', 'perfect', 'wonderful'];

        foreach ($veryNegative as $word) {
            if (strpos($content, $word) !== false) return 1;
        }
        
        foreach ($negative as $word) {
            if (strpos($content, $word) !== false) return 2;
        }
        
        foreach ($positive as $word) {
            if (strpos($content, $word) !== false) return 4;
        }
        
        foreach ($veryPositive as $word) {
            if (strpos($content, $word) !== false) return 5;
        }

        // If there's a response/action, it's slightly better
        if (!empty($response) || strtolower($status) === 'done') {
            return 3;
        }

        return 2; // Default to slightly negative for complaints
    }

    private function analyzeHospitalFeedback($content, $code, $status)
    {
        $content = strtolower($content);
        
        // Hospital-specific sentiment analysis
        $medicalPositive = [
            'satisfied', 'good service', 'professional', 'clean', 'efficient',
            'helpful', 'knowledgeable', 'caring', 'improved', 'better'
        ];
        
        $medicalNegative = [
            'delayed', 'poor', 'dirty', 'unhygienic', 'rude', 'unprofessional',
            'expired', 'broken', 'crowded', 'insufficient', 'discrimination',
            'unfair', 'not listening', 'ignored', 'uncomfortable', 'unsafe'
        ];

        $positiveScore = 0;
        $negativeScore = 0;
        $keywords = [];

        foreach ($medicalPositive as $word) {
            if (strpos($content, $word) !== false) {
                $positiveScore += 2;
                $keywords[] = $word;
            }
        }

        foreach ($medicalNegative as $word) {
            if (strpos($content, $word) !== false) {
                $negativeScore += 2;
                $keywords[] = $word;
            }
        }

        // Category-specific scoring
        $categoryScore = match($code) {
            'WELF' => -1, // Welfare complaints are usually negative
            'SECR' => -2, // Security issues are serious
            'ENVR' => -1, // Environment issues affect everyone
            'FEDB' => -1, // Feedback complaints indicate communication issues
            'CURR' => 0,  // Curriculum feedback can be constructive
            'TECH' => 0,  // Teaching feedback is often constructive
            default => 0
        };

        // Status affects scoring
        $statusScore = match(strtolower($status)) {
            'done' => 2,
            'in progress' => 1,
            default => -1
        };

        $sentimentScore = $positiveScore - $negativeScore + $statusScore;
        $urgencyScore = $negativeScore > 4 ? -3 : ($negativeScore > 2 ? -2 : -1);
        
        $sentiment = $sentimentScore > 0 ? 'positive' : ($sentimentScore < 0 ? 'negative' : 'neutral');
        
        return [
            'sentiment' => $sentiment,
            'sentiment_score' => $sentimentScore,
            'category_score' => $categoryScore,
            'urgency_score' => $urgencyScore,
            'total_score' => $sentimentScore + $categoryScore + $urgencyScore,
            'keywords' => implode(',', array_unique($keywords))
        ];
    }

    public function getCategoryStats()
    {
        $feedbacks = Feedback::whereJsonContains('metadata->hospital_department', 'Education')->get();
        
        $stats = [];
        foreach ($this->categoryMap as $code => $description) {
            $count = $feedbacks->filter(function($feedback) use ($code) {
                return isset($feedback->metadata['category_code']) && $feedback->metadata['category_code'] === $code;
            })->count();
            
            if ($count > 0) {
                $stats[$description] = $count;
            }
        }
        
        return $stats;
    }
}