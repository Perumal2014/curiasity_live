<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    //
    protected $table = 'profile';

    protected $fillable = [
        'profile_name',
        'status',
        'organization_id',
        'grp_type',
        'created_at',
        'updated_at'
    ];
}
