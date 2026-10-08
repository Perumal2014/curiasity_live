<?php

namespace Modules\Calendar\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Calendar;
use Modules\CourseSetting\Entities\Course;

class CalendarController1 extends Controller
{

    public function index()
    {
        $getCourses = Course::where('status', 1)->get();

        $courses = $getCourses->where('type', 1);
        $classes = $getCourses->where('type', 3);
        $calendars = Calendar::with('course')->get();
        // return $calendars;
        return view('calendar', compact('courses', 'classes', 'calendars'));
    }


    public function create()
    {
        return view('calendar::create');
    }


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'calendar_for' => 'required',
            'url' => 'sometimes|url',
        ];

        $request->validate($rules, validationMessage($rules));

        if ($request->calendar_for == 1) {
            $rules = [
                'course' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ];
        } else {
            $rules = [
                'class' => 'required',
            ];
        }
        $request->validate($rules, validationMessage($rules));

        try {
            if ($request->calendar_for == 1) {
                $topic_info = Course::find($request->course);
            } else {
                $topic_info = Course::find($request->class);

                $start_time = Carbon::parse($topic_info->class->time)->format('g:i A');
                $end_time = Carbon::parse($topic_info->class->time)->addMinutes($topic_info->class->duration)->format('g:i A');
            }

            $destination = 'public/uploads/events/';

            $calendar = new Calendar();
            $calendar->title = $request->title;
            $calendar->start = $request->calendar_for == 1 ? date('Y-m-d', strtotime($request->from_date)) : $topic_info->class->start_date;
            $calendar->end = $request->calendar_for == 1 ? date('Y-m-d', strtotime($request->to_date)) : $topic_info->class->end_date;
            $calendar->start_time = $request->calendar_for == 1 ? $request->start_time : $start_time;
            $calendar->end_time = $request->calendar_for == 1 ? $request->end_time : $end_time;
            $calendar->calendar_for = $request->calendar_for;
            $calendar->course_id = $request->calendar_for == 1 ? $request->course : $request->class;
            $calendar->description = $request->description;
            $calendar->calendar_url = $request->url;
            $calendar->color = $request->color;
            $calendar->created_by = Auth::user()->id;
            $calendar->banner = fileUpload($request->banner, $destination);
            $calendar->save();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('calendar::show');
    }


    public function edit($id)
    {
        return view('calendar::edit');
    }


    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'calendar_for' => 'required',
            'url' => 'sometimes|url',
        ];

        $request->validate($rules, validationMessage($rules));

        if ($request->calendar_for == 1) {
            $rules = [
                'course' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ];
        } else {
            $rules = [
                'class' => 'required',
            ];
        }
        $request->validate($rules, validationMessage($rules));

        try {
            if ($request->calendar_for == 1) {
                $topic_info = Course::find($request->course);
            } else {
                $topic_info = Course::find($request->class);

                $start_time = Carbon::parse($topic_info->class->time)->format('g:i A');
                $end_time = Carbon::parse($topic_info->class->time)->addMinutes($topic_info->class->duration)->format('g:i A');
            }

            $destination = 'public/uploads/events/';

            $calendar = Calendar::find($request->calendar_id);
            $calendar->title = $request->title;
            $calendar->start = $request->calendar_for == 1 ? date('Y-m-d', strtotime($request->from_date)) : $topic_info->class->start_date;
            $calendar->end = $request->calendar_for == 1 ? date('Y-m-d', strtotime($request->to_date)) : $topic_info->class->end_date;
            $calendar->start_time = $request->calendar_for == 1 ? $request->start_time : $start_time;
            $calendar->end_time = $request->calendar_for == 1 ? $request->end_time : $end_time;
            $calendar->calendar_for = $request->calendar_for;
            $calendar->course_id = $request->calendar_for == 1 ? $request->course : $request->class;
            $calendar->description = $request->description;
            $calendar->calendar_url = $request->url;
            $calendar->color = $request->color;
            if (isset($request->banner)) {
                $calendar->banner = fileUpload($request->banner, $destination);
            }

            $calendar->save();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        try {
            $calendar = Calendar::find($id);
            $calendar->delete();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }
}
