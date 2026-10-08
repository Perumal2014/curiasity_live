<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessUnit extends Model
{
    //
    protected $table = 'business_unit';

     protected $fillable = [
        'bu_name',
        'status',
        'profile_id',
        'grp_type',
        'organization_id',
        'created_at',
        'updated_at'
    ];
}
