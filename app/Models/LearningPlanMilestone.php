<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;
use App\Models\LearningPlan;

class LearningPlanMilestone extends Model
{
    protected $table = 'learning_plan_milestones';

    protected $fillable = [
        'learning_plan_id',
        'no_of_days',
        'deadline_type',
        'before_days',
        'after_days'
    ];

    public function learningPlan()
    {
        return $this->belongsTo(LearningPlan::class, 'learning_plan_id');
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'learning_plan_courses',
            'learning_plan_milestone_id',
            'course_id'
        );
    }
}