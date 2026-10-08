<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantPlans extends Model
{
    //
    protected $table = 'tenant_plans';

    protected $fillable = [
        'plan_name',
        'status',
    ];
}
