<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    protected $table = 'roles';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'created_at',
        'updated_at'
    ];
}
