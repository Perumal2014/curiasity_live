<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociateState extends Model
{
    //
    protected $table = 'associate_state';

     protected $fillable = [
        'state_name',
        'country_id',
        'status',
        'grp_type',
        'organization_id',
        'grp_type',
    ];
}
