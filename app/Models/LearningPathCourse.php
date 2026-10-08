<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Category;
use App\Models\LearningPathAssignments;
use App\Models\LearningPathStep;
use App\Models\LearningPathCourse;
use App\Models\LearningPathProgress;

class LearningPathCourse extends Model
{
    //
    protected $table = 'learning_path_courses';

    protected $fillable = [
        'learning_path_id',
        'step_id',
        'course_id',
        'created_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function progress()
    {
        return $this->hasOne(LearningPathProgress::class, 'course_id', 'course_id')
            ->where('user_id', auth()->id());
    }
}
