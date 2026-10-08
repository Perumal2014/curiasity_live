<?php

namespace Modules\Calendar\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Calendar\Entities\Calendar;
use Modules\Setting\Entities\UsedMedia;

class LmsEvent extends Model
{
    protected $guarded = ['id'];

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function calendar()
    {
        return $this->hasOne(Calendar::class, 'event_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($event) {
            if (isModuleActive('Calendar')) {
                $calendar = new Calendar();
                $calendar->title = $event->event_title;
                $calendar->start = date('Y-m-d', strtotime($event->from_date));
                $calendar->end = date('Y-m-d', strtotime($event->to_date));
                $calendar->start_time = $event->start_time;
                $calendar->end_time = $event->end_time;
                $calendar->calendar_for = null;
                $calendar->course_id = null;
                $calendar->description = $event->des;
                $calendar->calendar_url = $event->url;
                $calendar->color = '#F13D80';
                $calendar->banner = $event->uplad_image_file;
                $calendar->event_id = $event->id;
                $calendar->save();
            }

        });
        static::updated(function ($event) {
            if (isModuleActive('Calendar')) {
                $calendar = Calendar::find($event->calendar->id);
                $calendar->title = $event->event_title;
                $calendar->start = date('Y-m-d', strtotime($event->from_date));
                $calendar->end = date('Y-m-d', strtotime($event->to_date));
                $calendar->start_time = $event->start_time;
                $calendar->end_time = $event->end_time;
                $calendar->calendar_for = null;
                $calendar->course_id = null;
                $calendar->description = $event->des;
                $calendar->calendar_url = $event->url;
                $calendar->color = '#F13D80';
                $calendar->banner = $event->uplad_image_file;
                $calendar->save();
            }
        });
        static::deleting(function ($event) {
            if (isModuleActive('Calendar')) {
                $calendar = Calendar::find($event->calendar->id);
                $calendar->delete();
            }
        });
    }

    public function image_media()
    {
        return $this->morphOne(UsedMedia::class, 'usable')->where('used_for', 'uplad_image_file');
    }

}
