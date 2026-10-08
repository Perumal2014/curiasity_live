<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateCourse extends Model
{
    //
    protected $table = 'course_private';

    protected $fillable = [
        'course_id',
        'user_id',
        'grp_type',
        'created_at',
        'updated_at',
    ];
}
