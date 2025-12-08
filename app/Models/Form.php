<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'fields',
        'is_active',
        'is_public',
        'user_id',
        'share_token',
        'settings',
        'expires_at',
        'department',
        'department_subdivision',
    ];

    protected $casts = [
        'fields' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($form) {
            if (empty($form->slug)) {
                $form->slug = Str::slug($form->title . '-' . Str::random(6));
            }
            if (empty($form->share_token)) {
                $form->share_token = Str::random(32);
            }
        });
    }

    /**
     * Get the user that owns the form.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the responses for the form.
     */
    public function responses()
    {
        return $this->hasMany(FormResponse::class);
    }

    /**
     * Get the form URL for sharing
     */
    public function getShareUrlAttribute()
    {
        return route('forms.public.show', $this->share_token);
    }

    /**
     * Check if form is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if form can receive responses
     */
    public function canReceiveResponses()
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Get responses count
     */
    public function getResponsesCountAttribute()
    {
        return $this->responses()->count();
    }

    /**
     * Get all available departments
     */
    public static function getDepartments()
    {
        return [
            'kchs' => 'KCHS',
            'research' => 'Research',
            'gme' => 'GME',
            'cpd' => 'CPD',
        ];
    }

    /**
     * Get sub-departments for a given department
     */
    public static function getSubDepartments($department)
    {
        $subDepartments = [
            'kchs' => [
                'basic' => 'Basic and Post Basic',
            ],
            'research' => [
                'research' => 'Research',
            ],
            'gme' => [
                'interns_residents' => 'Interns and Residents',
                'visitor' => 'Visitor',
            ],
            'cpd' => [
                'elearning' => 'E-Learning',
                'simulation' => 'Simulation',
                'short_courses' => 'Short Courses',
            ],
        ];

        return $subDepartments[$department] ?? [];
    }
}
