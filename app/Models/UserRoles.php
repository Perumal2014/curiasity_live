<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Roles;

class UserRoles extends Model
{
    //
    protected $table = 'user_roles';

    protected $fillable = [
        'user_id',
        'role_id',
        'created_at',
        'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }
}
