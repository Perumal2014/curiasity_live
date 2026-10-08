<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalTrainers extends Model
{
    //
    protected $table = 'external_trainers';

    protected $fillable = [
        'name',
        'email_id',
        'mobile_no',
        'company_name',
        'status',
        'org_id',
        'created_at',
        'updated_at'
    ];
}
