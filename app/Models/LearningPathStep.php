<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPathStep extends Model
{
    //
    protected $table = 'learning_path_steps';

    protected $fillable = [
        'learning_path_id',
        'step_order',
        'step_title',
        'completion_count',
        'completion_type',
        'created_at',
        'updated_at'
    ];

    public function plancourses()
    {
        return $this->hasMany(LearningPathCourse::class, 'step_id');
    }
}
