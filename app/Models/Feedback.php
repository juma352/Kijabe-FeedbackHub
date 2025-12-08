<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Feedback extends Model
{
    protected $table = 'feedback';

    protected $fillable = [
        'source',
        'content',
        'metadata',
        'keyword',
        'sentiment',
        'original_sentiment',
        'sentiment_manually_edited',
        'departments',
        'action_required',
        'action_taken_at',
        'action_notes',
        'notified_emails',
        'rating',
    ];

    protected $casts = [
        'metadata' => 'array',
        'departments' => 'array',
        'notified_emails' => 'array',
        'sentiment_manually_edited' => 'boolean',
        'action_required' => 'boolean',
        'action_taken_at' => 'datetime',
    ];

    public function score()
    {
        return $this->hasOne(Score::class);
    }
    
    public function learnerExperience()
    {
        return $this->hasOne(LearnerExperience::class);
    }

    public function analyzeSentiment()
    {
        // Call Python microservice or Google NLP
        $response = Http::post('http://python-service.local/analyze', ['text' => $this->content]);
        $data = $response->json();
        $this->sentiment = $data['sentiment'];
        $this->keywords = implode(',', $data['keywords']);
        $this->save();

        // Trigger scoring
        $this->calculateScore();
    }
    public function calculateScore()
    {
        // Customizable rules from config or DB
        $sentimentScore = match ($this->sentiment) {
            'positive' => 5,
            'negative' => -5,
            default => 0,
        };
        $keywordScore = substr_count($this->keywords, 'urgent') * 2; // Example rule
        $urgencyScore = $this->urgency * 1.5;

        $total = $sentimentScore + $keywordScore + $urgencyScore;

        $this->score()->updateOrCreate([], [
            'sentiment_score' => $sentimentScore,
            'keyword_score' => $keywordScore,
            'urgency_score' => $urgencyScore,
            'total_score' => $total,
        ]);

        // Trigger alert if total > threshold
        if ($total > 20) {
            // Queue notification
            \App\Jobs\SendAlert::dispatch($this);
        }
    }

    /**
     * Update sentiment manually and preserve original
     */
    public function updateSentiment($newSentiment, $user = null)
    {
        // Store original sentiment if not already stored
        if (!$this->sentiment_manually_edited) {
            $this->original_sentiment = $this->sentiment;
        }
        
        $this->sentiment = $newSentiment;
        $this->sentiment_manually_edited = true;
        $this->save();
        
        // Recalculate score with new sentiment
        $this->calculateScore();
        
        return $this;
    }

    /**
     * Get available departments for categorization
     */
    public static function getAvailableDepartments()
    {
        return [
            'administration' => 'Administration',
            'clinical' => 'Clinical Services',
            'nursing' => 'Nursing',
            'pharmacy' => 'Pharmacy',
            'laboratory' => 'Laboratory',
            'radiology' => 'Radiology',
            'emergency' => 'Emergency Department',
            'outpatient' => 'Outpatient Services',
            'inpatient' => 'Inpatient Services',
            'finance' => 'Finance & Billing',
            'housekeeping' => 'Housekeeping',
            'security' => 'Security',
            'it' => 'IT Support',
            'hr' => 'Human Resources',
            'maintenance' => 'Maintenance',
        ];
    }

    /**
     * Get department emails for notifications
     */
    public static function getDepartmentEmails($departments = [])
    {
        $departmentEmails = [
            'administration' => ['admin@hospital.com'],
            'clinical' => ['clinical@hospital.com', 'head.clinical@hospital.com'],
            'nursing' => ['nursing@hospital.com', 'head.nurse@hospital.com'],
            'pharmacy' => ['pharmacy@hospital.com'],
            'laboratory' => ['lab@hospital.com'],
            'radiology' => ['radiology@hospital.com'],
            'emergency' => ['emergency@hospital.com', 'er.head@hospital.com'],
            'outpatient' => ['outpatient@hospital.com'],
            'inpatient' => ['inpatient@hospital.com'],
            'finance' => ['finance@hospital.com'],
            'housekeeping' => ['housekeeping@hospital.com'],
            'security' => ['security@hospital.com'],
            'it' => ['it@hospital.com'],
            'hr' => ['hr@hospital.com'],
            'maintenance' => ['maintenance@hospital.com'],
        ];

        $emails = [];
        foreach ($departments as $dept) {
            if (isset($departmentEmails[$dept])) {
                $emails = array_merge($emails, $departmentEmails[$dept]);
            }
        }

        return array_unique($emails);
    }

    /**
     * Mark action as required and assign departments
     */
    public function requireAction($departments = [], $notes = null)
    {
        $this->departments = $departments;
        $this->action_required = true;
        $this->action_notes = $notes;
        $this->save();

        return $this;
    }

    /**
     * Mark action as taken
     */
    public function markActionTaken($notes = null, $emails = [])
    {
        $this->action_taken_at = now();
        $this->action_notes = $notes;
        $this->notified_emails = $emails;
        $this->save();

        return $this;
    }
}
