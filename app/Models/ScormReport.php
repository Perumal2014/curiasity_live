<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;

class ScormReport extends Model
{
    use Tenantable;

    protected $guarded = ['id'];
}
