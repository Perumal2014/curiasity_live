<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommended extends Model
{
    //
    protected $table = 'course_recommend';
    protected $fillable = [
        'course_id',
        'role_id',
        'grp_type',
        'created_at',
        'updated_at',
    ];
}
