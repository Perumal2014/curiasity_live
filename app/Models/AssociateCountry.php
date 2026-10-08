<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociateCountry extends Model
{
    //
    protected $table = 'associate_country';

     protected $fillable = [
        'country_name',
        'organization_id',
        'grp_type',
        'status'
        
    ];
}
