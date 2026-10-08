<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'department';

     protected $fillable = [
        'dept_name',
        'bu_id',
        'status',
        'grp_type',
        'organization_id',
        'created_at',
        'updated_at'
    ];
}
