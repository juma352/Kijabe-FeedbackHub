<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearnerExperience extends Model
{
    protected $fillable = [
        'feedback_id',
        'total_score',
        'experience_level',
        'environment_score',
        'content_quality_score',
        'engagement_score',
        'support_system_score',
        'experience_data'
    ];

    protected $casts = [
        'experience_data' => 'array',
        'total_score' => 'decimal:2',
        'environment_score' => 'decimal:2',
        'content_quality_score' => 'decimal:2',
        'engagement_score' => 'decimal:2',
        'support_system_score' => 'decimal:2'
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
    
    /**
     * Get color class based on experience level
     */
    public function getLevelColorAttribute(): string
    {
        return match($this->experience_level) {
            'Exceptional' => 'text-green-800 bg-green-100',
            'Excellent' => 'text-green-700 bg-green-50',
            'Good' => 'text-blue-800 bg-blue-100',
            'Satisfactory' => 'text-yellow-800 bg-yellow-100',
            'Needs Improvement' => 'text-orange-800 bg-orange-100',
            'Critical' => 'text-red-800 bg-red-100',
            default => 'text-gray-800 bg-gray-100'
        };
    }
    
    /**
     * Get priority areas as formatted list
     */
    public function getPriorityAreasListAttribute(): string
    {
        $priorities = $this->experience_data['priority_areas'] ?? [];
        return implode(', ', $priorities);
    }
}
