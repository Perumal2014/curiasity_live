<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningPlanCourse extends Model
{
    //
    protected $table = 'learning_plan_courses';

     protected $fillable = [
        
        'learning_plan_id',
        'course_id',
        'order',
        'created_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
}
