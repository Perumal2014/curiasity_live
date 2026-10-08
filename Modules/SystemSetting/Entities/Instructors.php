<?php

namespace Modules\SystemSetting\Entities;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Model;

class Instructors extends Model
{
    use Tenantable;

    protected $guarded = [];

    protected $table = 'instructor_detail';

    

    public function instructor()
    {
        return $this->belongsTo(Staff::class, 'user_id', 'id')->withDefault();
    }
}
