<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLearningActivity extends Model
{
    //
    protected $table = 'user_learning_activity';

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'activity_type',
        'start_time',
        'end_time',
        'total_seconds'
    ];
}
