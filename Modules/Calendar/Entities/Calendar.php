<?php

namespace Modules\Calendar\Entities;

use App\Traits\Tenantable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\CourseSetting\Entities\Course;

class Calendar extends Model
{
    use Tenantable;

    protected $table = 'calendars';

    protected $fillable = [
        'title',
        'color',
        'start',
        'end',
        'calendar_url',
        'banner',
        'start_time',
        'end_time',
        'calendar_for',
        'course_id',
        'user_id',
        'description',
        'status',
        'event_id',
        'created_by',
        'updated_by',
        
    ];

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
