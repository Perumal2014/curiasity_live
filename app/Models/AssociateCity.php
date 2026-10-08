<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociateCity extends Model
{
    //
    protected $table = 'associate_city';

     protected $fillable = [
        'city_name',
        'state_id',
        'grp_type',
        'organization_id',
        'status'
    ];
}
