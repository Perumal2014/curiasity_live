<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;

class CourseApproval extends Model
{
    use HasFactory;

    protected $table = 'course_approvals';

    protected $fillable = [
        'user_id',
        'course_id',
        'approved_by',
        'status',
        'remarks',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    
    
}
