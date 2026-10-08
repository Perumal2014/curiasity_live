<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Modules\CourseSetting\Entities\Course;
use App\Models\LearningPlanGroup;
use App\Models\LearningPlanCourse;
use App\Models\LearningPlanReminder;
use App\Models\LearningPlanMilestone;
use App\Models\UserLearningPlanSchedule;


class LearningPlan extends Model
{
    //
    protected $table = 'learning_plans';

    protected $fillable = [
        'title',
        'occurs_when',
        'created_by',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'learning_plan_courses',
            'learning_plan_id',
            'course_id'
        );
    }

    public function groups()
    {
        return $this->hasMany(LearningPlanGroup::class, 'learning_plan_id');
    }

    public function milestones()
    {
        return $this->hasMany(LearningPlanMilestone::class, 'learning_plan_id');
    }

    public function reminders()
    {
        return $this->hasMany(LearningPlanReminder::class, 'learning_plan_id');
    }

    public function schedules()
    {
        return $this->hasMany(UserLearningPlanSchedule::class, 'learning_plan_id');
    }


}
