<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    //
    protected $table = 'user_transfer_activities';

    protected $fillable = [

        'user_id',
        'activity_type',
        'description',
        'page',
        'ip_address'
    ];
}
