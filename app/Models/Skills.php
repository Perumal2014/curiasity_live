<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\UserSkills;

class Skills extends Model
{
    //
    protected $table = 'skills';

    protected $fillable = [
        'name',
        'type',
        'status',
        'org_id',
    ];

    public function users_skills()
    {
        return $this->hasMany(UserSkills::class, 'skill_id');
    }
}
