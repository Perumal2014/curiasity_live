<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseWaitingList extends Model
{
    //
    use HasFactory;

    protected $table = 'course_waiting_list';

    protected $fillable = [
        'course_id',
        'user_id',
        'organization_id',
        'tenant_id',
        'position',
        'status',
        'notified',
        'notified_at',
    ];


}
