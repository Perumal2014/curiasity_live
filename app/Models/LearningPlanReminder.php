<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPlanReminder extends Model
{
    //
    protected $table = 'learning_plan_reminders';

     protected $fillable = [
        'milestone_id',
        'learning_plan_id',
        'reminder_type',
        'created_at',
        'updated_at'
    ];
}
