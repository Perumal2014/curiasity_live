<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    //
     protected $table = 'management';

     protected $fillable = [
        'management_name',
        'organization_id',
        'grp_type',
        'status'
    ];
}
