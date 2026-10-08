<?php

namespace Modules\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\EventNotification;
use App\Traits\UploadMedia;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Modules\Entities\LmsEvent;
use Modules\Http\Requests\EventRequest;
use Modules\RolePermission\Entities\Role;

class EventController extends Controller
{
    use UploadMedia;

    public function index(Request $request)
    {
        try {
            $events = LmsEvent::get();

            return view('calendar::event.eventList', compact('events'));
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
         $validate_rules = [
            'event_title' => 'required|max:255',
            'for_whom' => 'required|min:0',
            'from_date' => 'required|min:0',
            'to_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'image' => 'required',
            'host_type' => 'required',
            'event_des' => 'required',
        ];
         if ($request->host_type == 1) {
             $validate_rules['instructor'] = 'required';

        } else {
             $validate_rules['host_name'] = 'required';
        }


        $request->validate($validate_rules, validationMessage($validate_rules));

        if (!isModuleActive('Calendar')) {
            Toastr::error(trans('frontend.Calendar module is required'), trans('common.Failed'));
             return back();
        }
        try {


            $user = Auth()->user();
            $login_id = $user ? $user->id : $request->login_id;
            $events = new LmsEvent();
            $events->event_title = $request->event_title;
            $events->for_whom = $request->for_whom;
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->start_time = $request->start_time;
            $events->end_time = $request->end_time;
            $events->url = $request->event_url;
            $events->host_type = $request->host_type;
            if ($request->host_type == 1) {
                $events->host = $request->instructor;
            } else {
                $events->host = $request->host_name;
            }

            $events->created_by = $login_id;
            $events->save();

            if ($request->image) {
                $events->uplad_image_file = $this->generateLink($request->image, $events->id, get_class($events), 'uplad_image_file');
            }
            $events->save();


            if ($request->for_whom == 'All') {
                $users = User::where('status', 1)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            } elseif ($request->for_whom == 'Instructor') {
                $users = User::where('status', 1)->where('role_id', 2)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            } elseif ($request->for_whom == 'Student') {
                $users = User::where('status', 1)->where('role_id', 3)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            } elseif ($request->for_whom == 'Staff') {
                $users = User::where('status', 1)->where('role_id', 4)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            }
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect('communicate/event');
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function create()
    {
        try {
            $roles = Role::where('id', '!=', 1)->get();
            $instructors = User::where('role_id', 2)->where('status', 1)->get();
            return view('calendar::event.add_event', compact('roles', 'instructors'));
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $roles = Role::where('id', '!=', 1)->get();
            $instructors = User::where('role_id', 2)->where('status', 1)->get();
            $editData = LmsEvent::find($id);
            return view('calendar::event.add_event', compact('editData', 'instructors', 'roles'));
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function update(EventRequest $request, $id)
    {
        $validate_rules = [
            'event_title' => 'required|max:255',
            'for_whom' => 'required|min:0',
            'from_date' => 'required|min:0',
            'to_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'host_type' => 'required',
            'event_des' => 'required',
        ];
        $request->validate($validate_rules, validationMessage($validate_rules));
        if ($request->host_type == 1) {
            $validate_rules = [
                'instructor' => 'required',
            ];
        } else {
            $validate_rules = [
                'host_name' => 'required',
            ];
        }

        $request->validate($validate_rules, validationMessage($validate_rules));

        try {

            $user = Auth()->user();

            $login_id = $user ? $user->id : $request->login_id;
            $events = LmsEvent::find($id);
            $events->event_title = $request->event_title;
            $events->for_whom = $request->for_whom;
            $events->event_des = $request->event_des;
            $events->event_location = $request->event_location;
            $events->from_date = date('Y-m-d', strtotime($request->from_date));
            $events->to_date = date('Y-m-d', strtotime($request->to_date));
            $events->start_time = $request->start_time;
            $events->end_time = $request->end_time;
            $events->url = $request->event_url;
            $events->host_type = $request->host_type;
            if ($request->host_type == 1) {
                $events->host = $request->instructor;
            } else {
                $events->host = $request->host_name;
            }
            $events->updated_by = $login_id;
            $events->uplad_image_file = null;

            $events->update();

            $this->removeLink($events->id, get_class($events));

            if ($request->image) {
                $events->uplad_image_file = $this->generateLink($request->image, $events->id, get_class($events), 'uplad_image_file');
            }
            $events->save();


            if ($request->for_whom == 'All') {
                $users = User::where('status', 1)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            } elseif ($request->for_whom == 'Instructor') {
                $users = User::where('status', 1)->where('role_id', 2)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            } elseif ($request->for_whom == 'Student') {
                $users = User::where('status', 1)->where('role_id', 3)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            } elseif ($request->for_whom == 'Staff') {
                $users = User::where('status', 1)->where('role_id', 4)->get();
                foreach ($users as $user) {
                    EventNotification::dispatch($events, $user);
                }
            }
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect('communicate/event');

        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function deleteEventView(Request $request, $id)
    {
        try {

            return view('calendar::event.deleteEventView', compact('id'));
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function deleteEvent(Request $request, $id)
    {
        try {
            LmsEvent::destroy($id);

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();

        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function download($file_name = null)
    {
        $file = public_path() . '/uploads/events/' . $file_name;
        if (file_exists($file)) {
            return Response::download($file);
        } else {
            return redirect()->back();
        }
    }
}
