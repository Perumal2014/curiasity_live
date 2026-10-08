<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLearningPlanSchedule extends Model
{
    //
    protected $table = 'user_learning_plan_schedules';

     protected $fillable = [
        'user_id',
        'group_id',
        'group_type',
        'learning_plan_id',
        'course_id',
        'start_date',
        'due_date',
        'status',
        'reminder_before_sent',
        'reminder_after_sent',
        'created_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
