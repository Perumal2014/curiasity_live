<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\FeedbackReminders;
use App\Models\FeedbackAllocation;


class Feedbacks extends Model
{
    //
    protected $table = 'feedbacks';

    protected $fillable = [
        'feedback_name',
        'course_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function reminders()
    {
        return $this->hasOne(FeedbackReminders::class, 'feedback_id');
    }

    public function feedbackAllocations()
    {
        return $this->hasMany(FeedbackAllocations::class, 'feedback_id');
    }
}
