<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPathProgress extends Model
{
    //
    protected $table = 'learning_path_progress';

    protected $fillable = [
        'user_id',
        'learning_path_id',
        'course_id',
        'progress_percentage',
        'status',
        'created_at',
        'updated_at'
    ];
}
