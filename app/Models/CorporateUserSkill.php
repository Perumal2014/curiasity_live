<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateUserSkill extends Model
{
    use HasFactory;

    protected $table = 'corporate_user_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skill()
    {
        return $this->belongsTo(CorporateSkill::class);
    }
}
