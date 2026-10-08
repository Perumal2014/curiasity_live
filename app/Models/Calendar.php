<?php

namespace App\Models;

use App\Traits\Tenantable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;

class Calendar extends Model
{
    use Tenantable;

    protected $fillable = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id')->withDefault();
    }

    public function event()
    {
        return $this->belongsTo(LmsEvent::class, 'event_id', 'id')->withDefault();
    }

    public function getStartAttribute()
    {
        $start = $this->attributes['start'];
        $startTime = $this->attributes['start_time'];
        return Carbon::createFromFormat('Y-m-d h:i A', $start . ' ' . $startTime);
    }

    public function getEndAttribute()
    {
        $end = $this->attributes['end'];
        $endTime = $this->attributes['end_time'];
        $date = Carbon::createFromFormat('Y-m-d h:i A', $end . ' ' . $endTime);
//        $end = $this->attributes['end'];
//        $date = Carbon::createFromFormat('Y-m-d', $end);
        $daysToAdd = 0;
        return $date->addDays($daysToAdd);
    }
}
