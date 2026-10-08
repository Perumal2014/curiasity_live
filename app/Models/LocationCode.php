<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCode extends Model
{
    //
    protected $table = 'location_code';

     protected $fillable = [
        'location_code',
        'country_id',
        'grp_type',
        'organization_id',
        'status'
    ];
}
