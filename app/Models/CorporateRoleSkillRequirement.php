<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateRoleSkillRequirement extends Model
{
    use HasFactory;

    protected $table = 'corporate_role_skill_requirements';

    protected $fillable = [
        'role_id',
        'skill_id',
        'required_level',
    ];

    public function skill()
    {
        return $this->belongsTo(CorporateSkill::class);
    }

    // role_id may map to organization module’s roles table
    public function role()
    {
        return $this->belongsTo(\Modules\Organization\Entities\OrgRole::class, 'role_id');
    }
}
