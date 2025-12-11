<?php

namespace App\Services;

use App\Models\Feedback;

class FeedbackResolutionService
{
    /**
     * Calculate resolution metrics for feedback requiring action
     */
    public function getResolutionMetrics()
    {
        $feedbacks = Feedback::with('score')->get();
        $actionRequired = $feedbacks->where('action_required', true);
        $resolved = $actionRequired->whereNotNull('action_taken_at');
        $pending = $actionRequired->whereNull('action_taken_at');
        
        $totalActionRequired = $actionRequired->count();
        $resolvedCount = $resolved->count();
        $pendingCount = $pending->count();
        
        // Calculate resolution rate
        $resolutionRate = $totalActionRequired > 0 
            ? round(($resolvedCount / $totalActionRequired) * 100, 1) 
            : 0;
        
        // Calculate average resolution time (in hours)
        $avgResolutionTime = 0;
        $resolutionTimes = [];
        
        if ($resolvedCount > 0) {
            foreach ($resolved as $feedback) {
                $timeToResolve = $feedback->created_at->diffInHours($feedback->action_taken_at);
                $resolutionTimes[] = $timeToResolve;
            }
            $avgResolutionTime = round(array_sum($resolutionTimes) / count($resolutionTimes), 1);
        }
        
        // Resolution by sentiment
        $resolutionBySentiment = [
            'negative' => [
                'total' => $actionRequired->where('sentiment', 'negative')->count(),
                'resolved' => $resolved->where('sentiment', 'negative')->count(),
            ],
            'neutral' => [
                'total' => $actionRequired->where('sentiment', 'neutral')->count(),
                'resolved' => $resolved->where('sentiment', 'neutral')->count(),
            ],
            'positive' => [
                'total' => $actionRequired->where('sentiment', 'positive')->count(),
                'resolved' => $resolved->where('sentiment', 'positive')->count(),
            ],
        ];
        
        // Calculate rates for each sentiment
        foreach ($resolutionBySentiment as $sentiment => &$data) {
            $data['rate'] = $data['total'] > 0 
                ? round(($data['resolved'] / $data['total']) * 100, 1) 
                : 0;
        }
        
        // Resolution by department (if departments are assigned)
        $resolutionByDepartment = [];
        $departments = Feedback::getAvailableDepartments();
        
        foreach ($departments as $key => $name) {
            $deptFeedback = $actionRequired->filter(function ($feedback) use ($key) {
                $feedbackDepartments = $feedback->departments;
                
                // Handle case where departments might be a string or null
                if (is_string($feedbackDepartments)) {
                    $feedbackDepartments = json_decode($feedbackDepartments, true) ?? [];
                } elseif (!is_array($feedbackDepartments)) {
                    $feedbackDepartments = [];
                }
                
                return in_array($key, $feedbackDepartments);
            });
            
            $deptResolved = $deptFeedback->whereNotNull('action_taken_at');
            $deptTotal = $deptFeedback->count();
            
            if ($deptTotal > 0) {
                $resolutionByDepartment[$name] = [
                    'total' => $deptTotal,
                    'resolved' => $deptResolved->count(),
                    'pending' => $deptTotal - $deptResolved->count(),
                    'rate' => round(($deptResolved->count() / $deptTotal) * 100, 1)
                ];
            }
        }
        
        // Recent resolution trend (last 30 days)
        $recentResolved = $resolved->filter(function ($feedback) {
            return $feedback->action_taken_at >= now()->subDays(30);
        });
        
        return [
            'total_action_required' => $totalActionRequired,
            'resolved' => $resolvedCount,
            'pending' => $pendingCount,
            'resolution_rate' => $resolutionRate,
            'avg_resolution_time_hours' => $avgResolutionTime,
            'avg_resolution_time_days' => round($avgResolutionTime / 24, 1),
            'resolution_by_sentiment' => $resolutionBySentiment,
            'resolution_by_department' => $resolutionByDepartment,
            'recent_resolutions' => $recentResolved->count(),
        ];
    }
    
    /**
     * Get resolution time for a specific feedback
     */
    public function getResolutionTime(Feedback $feedback)
    {
        if (!$feedback->action_required) {
            return null;
        }
        
        if ($feedback->action_taken_at) {
            $hours = $feedback->created_at->diffInHours($feedback->action_taken_at);
            $days = floor($hours / 24);
            $remainingHours = $hours % 24;
            
            return [
                'resolved' => true,
                'hours' => $hours,
                'days' => $days,
                'remaining_hours' => $remainingHours,
                'formatted' => $this->formatResolutionTime($days, $remainingHours),
                'resolved_at' => $feedback->action_taken_at->format('M j, Y H:i'),
            ];
        }
        
        // Calculate pending time
        $hours = $feedback->created_at->diffInHours(now());
        $days = floor($hours / 24);
        $remainingHours = $hours % 24;
        
        return [
            'resolved' => false,
            'hours' => $hours,
            'days' => $days,
            'remaining_hours' => $remainingHours,
            'formatted' => $this->formatResolutionTime($days, $remainingHours) . ' (pending)',
            'created_at' => $feedback->created_at->format('M j, Y H:i'),
        ];
    }
    
    /**
     * Format resolution time in a human-readable format
     */
    private function formatResolutionTime($days, $hours)
    {
        if ($days > 0) {
            return $days . ' day' . ($days > 1 ? 's' : '') . ($hours > 0 ? ', ' . $hours . ' hour' . ($hours > 1 ? 's' : '') : '');
        }
        
        return $hours . ' hour' . ($hours > 1 ? 's' : '');
    }
}
