<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackAllocation extends Model
{
    //
    protected $table = 'feedback_allocation';

    protected $fillable = [
        'course_id',
        'feedback_id',
        'enrollment_id',
        'user_id',
        'allocated_at',
        'course_completed_at',
        'last_reminder_sent_at',
        'reminder_count',
        'status',
        'created_at',
        'updated_at'
    ];

    public function feedback()
    {
        return $this->belongsTo(Feedbacks::class, 'feedback_id');
    }
}
