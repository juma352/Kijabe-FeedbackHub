<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\DepartmentHead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FeedbackActionService
{
    /**
     * Send bulk notifications to departments about feedback requiring action
     */
    public function sendBulkNotifications(array $feedbackIds, array $departments, ?string $customMessage = null)
    {
        $feedbacks = Feedback::whereIn('id', $feedbackIds)
            ->where('action_required', true)
            ->get();

        if ($feedbacks->isEmpty()) {
            return ['success' => false, 'message' => 'No feedback items found requiring action'];
        }

        // Get department emails from department heads table
        $emails = $this->getDepartmentEmails($departments);
        
        if (empty($emails)) {
            return ['success' => false, 'message' => 'No email addresses found for selected departments'];
        }

        $sentEmails = [];
        $failedEmails = [];

        try {
            // Prepare email data
            $emailData = [
                'feedbacks' => $feedbacks,
                'departments' => $departments,
                'customMessage' => $customMessage,
                'totalCount' => $feedbacks->count(),
                'negativeCount' => $feedbacks->where('sentiment', 'negative')->count(),
                'actionRequiredDate' => now()->format('Y-m-d H:i:s'),
            ];

            // Send emails
            foreach ($emails as $email) {
                try {
                    Mail::send('emails.feedback-action-required', $emailData, function ($message) use ($email, $feedbacks) {
                        $message->to($email)
                            ->subject('Action Required: Feedback Issues Need Attention - ' . $feedbacks->count() . ' items');
                    });
                    $sentEmails[] = $email;
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$email}: " . $e->getMessage());
                    $failedEmails[] = $email;
                }
            }

            // Update feedback records with notification info
            foreach ($feedbacks as $feedback) {
                $feedback->markActionTaken(
                    "Bulk notification sent to: " . implode(', ', $departments),
                    $sentEmails
                );
            }

            return [
                'success' => true,
                'message' => 'Notifications sent successfully to ' . count($sentEmails) . ' recipient(s)',
                'sentTo' => $sentEmails,
                'failed' => $failedEmails,
                'feedbackCount' => $feedbacks->count(),
            ];

        } catch (\Exception $e) {
            Log::error('Bulk notification failed: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send notifications: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get email addresses for departments from department_heads table
     */
    private function getDepartmentEmails(array $departments): array
    {
        $emails = [];
        
        foreach ($departments as $departmentKey) {
            $departmentHead = DepartmentHead::getByDepartment($departmentKey);
            
            if ($departmentHead) {
                $emails = array_merge($emails, $departmentHead->getAllEmails());
            }
        }
        
        return array_unique(array_filter($emails));
    }
        }
    }

    /**
     * Auto-categorize feedback into departments based on content keywords
     */
    public function suggestDepartments(Feedback $feedback)
    {
        $content = strtolower($feedback->content);
        $suggestedDepartments = [];

        // Define keyword mappings for departments
        $departmentKeywords = [
            'clinical' => ['doctor', 'physician', 'treatment', 'diagnosis', 'clinical', 'medical', 'procedure'],
            'nursing' => ['nurse', 'nursing', 'ward', 'bedside', 'care', 'injection', 'medication'],
            'pharmacy' => ['pharmacy', 'medication', 'prescription', 'drug', 'medicine', 'pharmacist'],
            'laboratory' => ['lab', 'laboratory', 'test', 'blood', 'sample', 'results'],
            'radiology' => ['x-ray', 'scan', 'imaging', 'radiology', 'mri', 'ct scan'],
            'emergency' => ['emergency', 'urgent', 'ambulance', 'er', 'trauma'],
            'administration' => ['billing', 'appointment', 'scheduling', 'front desk', 'reception'],
            'housekeeping' => ['clean', 'dirty', 'hygiene', 'housekeeping', 'maintenance', 'room'],
            'security' => ['security', 'safety', 'theft', 'violence', 'guard'],
            'finance' => ['bill', 'payment', 'insurance', 'cost', 'charge', 'finance'],
        ];

        foreach ($departmentKeywords as $department => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($content, $keyword)) {
                    if (!in_array($department, $suggestedDepartments)) {
                        $suggestedDepartments[] = $department;
                    }
                }
            }
        }

        return $suggestedDepartments;
    }

    /**
     * Analyze feedback urgency and priority
     */
    public function analyzeFeedbackUrgency(Feedback $feedback)
    {
        $content = strtolower($feedback->content);
        $urgencyKeywords = [
            'high' => ['urgent', 'emergency', 'critical', 'serious', 'dangerous', 'immediate', 'asap'],
            'medium' => ['important', 'soon', 'concern', 'issue', 'problem', 'needs attention'],
            'low' => ['suggestion', 'feedback', 'recommend', 'could', 'maybe', 'consider']
        ];

        $urgencyScore = 0;
        $detectedLevel = 'low';

        foreach ($urgencyKeywords['high'] as $keyword) {
            if (str_contains($content, $keyword)) {
                $urgencyScore += 3;
            }
        }

        foreach ($urgencyKeywords['medium'] as $keyword) {
            if (str_contains($content, $keyword)) {
                $urgencyScore += 2;
            }
        }

        if ($urgencyScore >= 6) {
            $detectedLevel = 'high';
        } elseif ($urgencyScore >= 3) {
            $detectedLevel = 'medium';
        }

        return [
            'level' => $detectedLevel,
            'score' => $urgencyScore,
            'requiresAction' => $urgencyScore >= 3 || $feedback->sentiment === 'negative'
        ];
    }

    /**
     * Generate action report for management
     */
    public function generateActionReport($startDate = null, $endDate = null)
    {
        $query = Feedback::where('action_required', true);
        
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $feedbacks = $query->get();

        $report = [
            'total_action_required' => $feedbacks->count(),
            'actions_taken' => $feedbacks->whereNotNull('action_taken_at')->count(),
            'pending_actions' => $feedbacks->whereNull('action_taken_at')->count(),
            'by_department' => [],
            'by_sentiment' => [
                'negative' => $feedbacks->where('sentiment', 'negative')->count(),
                'neutral' => $feedbacks->where('sentiment', 'neutral')->count(),
                'positive' => $feedbacks->where('sentiment', 'positive')->count(),
            ],
            'response_time_avg' => 0,
        ];

        // Calculate department breakdown
        $departments = Feedback::getAvailableDepartments();
        foreach ($departments as $key => $name) {
            $count = $feedbacks->filter(function ($feedback) use ($key) {
                return in_array($key, $feedback->departments ?? []);
            })->count();
            
            if ($count > 0) {
                $report['by_department'][$name] = $count;
            }
        }

        // Calculate average response time
        $actionsTaken = $feedbacks->whereNotNull('action_taken_at');
        if ($actionsTaken->count() > 0) {
            $totalHours = $actionsTaken->sum(function ($feedback) {
                return $feedback->created_at->diffInHours($feedback->action_taken_at);
            });
            $report['response_time_avg'] = round($totalHours / $actionsTaken->count(), 1);
        }

        return $report;
    }
}