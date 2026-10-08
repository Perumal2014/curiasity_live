<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\Lesson;
use App\Models\User;


class AttendanceSession extends Model
{
    protected $fillable = [
        'organization_id',
        'course_id',
        'lesson_id',
        'type'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }


}
