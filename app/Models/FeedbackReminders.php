<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackReminders extends Model
{
    //
    protected $table = 'feedback_reminders';

    protected $fillable = [
        'course_id',
        'reminder_name',
        'feedback_id',
        'when_to_send',
        'days_after_completion',
        'recurrence',
        'for_days',
        'created_at',
        'updated_at'
    ];
}
