<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $table = 'scores';

    protected $fillable = [
        'feedback_id',
        'sentiment_score',
        'keyword_score',
        'urgency_score',
        'total_score',
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }
}
