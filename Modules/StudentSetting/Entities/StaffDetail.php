<?php

namespace Modules\StudentSetting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\StudentSetting\Database\Factories\StudentDetailFactory;

class StaffDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'staffs';

    protected $guarded = ['id'];

    protected $fillable = ['employee_id', 'user_id', 'department_id', 'handle_year', 'phone', 'qualification'];

    

    // protected static function newFactory(): StudentDetailFactory
    // {
    //     // return StudentDetailFactory::new();
    // }
}
