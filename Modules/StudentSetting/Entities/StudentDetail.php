<?php

namespace Modules\StudentSetting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\StudentSetting\Database\Factories\StudentDetailFactory;

class StudentDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'student_detail';

    protected $guarded = ['id'];

    protected $fillable = ['user_id', 'department_id', 'unique_code', 'learning_level', 'student_roll_no', 'parent_no', 'student_year'];

    

    // protected static function newFactory(): StudentDetailFactory
    // {
    //     // return StudentDetailFactory::new();
    // }
}
