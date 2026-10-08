<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateSkill extends Model
{
    use HasFactory;

    protected $table = 'corporate_skills';

    protected $fillable = [
        'name',
        'category',
        'status',
    ];

    // A skill belongs to many users
    public function users()
    {
        return $this->belongsToMany(User::class, 'corporate_user_skills')
            ->withPivot('level')
            ->withTimestamps();
    }


    public function roles()
{
    return $this->belongsToMany(
        \Modules\RolePermission\Entities\Role::class,
        'corporate_role_skill_requirements',
        'skill_id', // foreign key for skill
        'role_id'   // foreign key for role
    )->withPivot('required_level')
     ->withTimestamps();
}

    // A skill belongs to many job roles
    // public function roles()
    // {
    //     return $this->belongsToMany(CorporateRoleSkillRequirement::class, 'corporate_role_skill_requirements')
    //         ->withPivot('required_level')
    //         ->withTimestamps();
    // }
}
