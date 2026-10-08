<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkills extends Model
{
    //
    protected $table = 'users_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'level',
        'created_at',
        'updated_at',
    ];

    public $timestamps = true;
}
