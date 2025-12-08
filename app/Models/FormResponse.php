<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'responses',
        'respondent_email',
        'respondent_name',
        'ip_address',
        'user_agent',
        'submitted_at',
    ];

    protected $casts = [
        'responses' => 'array',
        'submitted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($response) {
            if (empty($response->submitted_at)) {
                $response->submitted_at = now();
            }
        });
    }

    /**
     * Get the form that owns the response.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get a specific field response
     */
    public function getFieldResponse($fieldKey)
    {
        return $this->responses[$fieldKey] ?? null;
    }

    /**
     * Check if response has a specific field
     */
    public function hasField($fieldKey)
    {
        return array_key_exists($fieldKey, $this->responses ?? []);
    }
}
