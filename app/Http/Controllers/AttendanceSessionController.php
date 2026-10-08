<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\AttendanceSession;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use App\Models\Tenants;
use App\Models\Attendance;
use App\Models\User;
use App\Imports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;
use Brian2694\Toastr\Facades\Toastr;



class AttendanceSessionController extends Controller
{


public function index($tenant_slug = null)
{
    $organizationId = Auth::user()->organization_id;

    $query = Course::where('organization_id', $organizationId)
        ->whereHas('attendanceSessions')
        ->withCount('attendanceSessions');

    // Instructor
    if (Auth::user()->role_id != 1 && Auth::user()->role_id != 8 && Auth::user()->role_id != 11) {
        $query->whereHas('attendanceSessions.lesson', function ($q) {
            $q->where('instructor_id', Auth::id());
        });
    }

    $courses = $query->orderBy('title')->get();

    return view('attendance_sessions.index', compact('courses'));
}

public function Sessions($tenant_slug = null, $course)
{
    $organizationId = Auth::user()->organization_id;

    $course = Course::where('organization_id', $organizationId)
        ->findOrFail($course);

    $query = AttendanceSession::where('organization_id', $organizationId)
        ->where('course_id', $course->id)
        ->with([
            'course:id,title',
            'lesson:id,name,start_date,start_time,end_time,instructor_id',
            'lesson.instructor:id,name'
        ]);

    if (Auth::user()->role_id != 1 && Auth::user()->role_id != 8 && Auth::user()->role_id != 11) {
        $query->whereHas('lesson', function ($q) {
            $q->where('instructor_id', Auth::id());
        });
    }

    $sessions = $query->latest()->get();

    return view('attendance_sessions.sessions', compact('course', 'sessions'));
}


//     public function index($tenant_slug = null)
// {
//     $organizationId = Auth::user()->organization_id;

//     if (Auth::user()->role_id == 1 || Auth::user()->role_id == 11) {

//         $sessions = AttendanceSession::where('organization_id', $organizationId)
//             ->with([
//                 'course:id,title',
//                 'lesson:id,name,start_date,start_time,end_time,instructor_id',
//                 'lesson.instructor:id,name'
//             ])
//             ->latest()
//             ->get();

//     } else {

//         $sessions = AttendanceSession::where('organization_id', $organizationId)
//             ->whereHas('lesson', function ($q) {
//                 $q->where('instructor_id', Auth::id());
//             })
//             ->with([
//                 'course:id,title',
//                 'lesson:id,name,start_date,start_time,end_time,instructor_id',
//                 'lesson.instructor:id,name'
//             ])
//             ->latest()
//             ->get();
//     }

//     return view('attendance_sessions.index', compact('sessions'));
// }


    // public function create($tenant_slug = null)
    // {
    //     $organizationId = Auth::user()->organization_id;

    //     $courses = Course::where('organization_id', $organizationId)->where('type', 3)->get();

    //     return view('attendance_sessions.create', compact('courses'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'course_id' => 'required|exists:courses,id',
    //         'session_date' => 'required|date',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //     ]);

    //     AttendanceSession::create([
    //         'organization_id' => Auth::user()->organization_id,
    //         'course_id' => $request->course_id,
    //         'session_date' => $request->session_date,
    //         'start_time' => $request->start_time,
    //         'end_time' => $request->end_time,
    //         'type' => $request->type ?? 'face_to_face',
    //     ]);

    //     return back()->with('success', 'Attendance Session Created Successfully');
    // }

    public function mark($tenant_slug = null, $id)
    {
        $organizationId = Auth::user()->organization_id;


        $session = AttendanceSession::where('id', $id)->where('organization_id', $organizationId)->firstOrFail();

        $enrolledUserIds = CourseEnrolled::where('course_id', $session->course_id)->pluck('user_id');

        $users = User::whereIn('id', $enrolledUserIds)
            ->where('organization_id', $organizationId)
            ->where('role_id', 3)
            ->get();



        $existingAttendance = Attendance::where('attendance_session_id', $id)->get()->keyBy('user_id');

        return view('attendance_sessions.mark', compact(
            'session',
            'users',
            'existingAttendance'
        ));
    }



    public function saveAttendance(Request $request, $tenant_slug = null, $id)
    {
        foreach ($request->attendance as $user_id => $status) {

            Attendance::updateOrCreate(
                [
                    'attendance_session_id' => $id,
                    'user_id' => $user_id
                ],
                [
                    'status' => $status
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance Saved Successfully');
    }



    public function generateQr($tenant_slug, $id)
    {
        $organizationId = Auth::user()->organization_id;

        $session = AttendanceSession::where('id', $id)->where('organization_id', $organizationId)->firstOrFail();

        $session->qr_token = Str::random(40);
        $session->qr_expires_at = Carbon::now()->addMinutes(30);
        $session->save();

        $qrUrl = route('attendance.scan', [
            'tenant_slug' => $tenant_slug,
            'token' => $session->qr_token
        ]);

        return view('attendance_sessions.qr', compact('session', 'qrUrl'));
    }

    public function scanQr($tenant_slug, $token)
    {
        // print_r($tenant_slug,); 


        // print_r($token); exit;

        $session = AttendanceSession::where('qr_token', $token)->first();

        if (!$session) {
            abort(404, 'Invalid QR Code');
        }

        // if (Carbon::now()->greaterThan($session->qr_expires_at)) {
        //     return view('attendance_sessions.qr_expired');
        // }

        if (!auth('web')->check()) {
            
            session(['qr_redirect' => url()->full()]);
            
            return redirect()->route('tenant.login', [
                'tenant_slug' => $tenant_slug
                ]);
            // return redirect()->guest(route('tenant.login', ['tenant_slug' => $tenant_slug]));
            
        }

         if (Carbon::now()->greaterThan($session->qr_expires_at)) {
            return view('attendance_sessions.qr_expired');
        }


        $user = auth('web')->user();

        $isEnrolled = CourseEnrolled::where('course_id', $session->course_id)->where('user_id', $user->id)->exists();

        if (!$isEnrolled) {
            abort(401, 'Attendance marking is restricted to enrolled participants only. Please verify your enrollment status or contact the administrator for assistance.');
        }

        $alreadyMarked = Attendance::where([
            'attendance_session_id' => $session->id,
            'user_id' => $user->id
        ])->exists();

        if ($alreadyMarked) {
            return view('attendance_sessions.already_marked');
        }

        return view('attendance_sessions.confirm_attendance', compact('session'));
    }


    public function qrSubmit(Request $request, $tenant_slug = null, $id)
    {
        $organizationId = Auth::user()->organization_id;

        $session = AttendanceSession::where('id', $id)->where('organization_id', $organizationId)->firstOrFail();

        if (Carbon::now()->greaterThan($session->qr_expires_at)) {
            return view('attendance_sessions.qr_expired');
        }

        $user = Auth::user();

        $isEnrolled = CourseEnrolled::where('course_id', $session->course_id)->where('user_id', $user->id)->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        Attendance::updateOrCreate(
            [
                'attendance_session_id' => $id,
                'user_id' => Auth::id()
            ],
            [
                'status' => 'Attended'
            ]
        );

        return view('attendance_sessions.success');
    }

    public function viewAttendance($tenant_slug = null, $id)
    {
        $organizationId = Auth::user()->organization_id;

        $session = AttendanceSession::where('id', $id)->where('organization_id', $organizationId)->firstOrFail();


        $enrolledUserIds = CourseEnrolled::where('course_id', $session->course_id)
            ->pluck('user_id');

        $students = User::whereIn('id', $enrolledUserIds)
            ->where('organization_id', $organizationId)
            ->where('role_id', 3)
            ->get();


        // $attendanceRecords = Attendance::where('attendance_session_id', $id)
        //     ->get()
        //     ->keyBy('user_id');

        $attendanceRecords = Attendance::where('attendance_session_id', $id)
            ->whereIn('user_id', $students->pluck('id'))
            ->get()
            ->keyBy('user_id');

        return view('attendance_sessions.view_attendance', compact(
            'session',
            'students',
            'attendanceRecords'
        ));
    }
    
    
    public function importPage($tenant_slug, $id)
    {
        $organizationId = Auth::user()->organization_id;
        
        $session = AttendanceSession::where('id', $id)
                            ->where('organization_id', $organizationId)->firstOrFail();
                            
        return view('attendance_sessions.import_attendance', compact('session'));
    }


    public function importAttendance(Request $request, $tenant_slug, $id)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx'
        ]);
        
        $organizationId = Auth::user()->organization_id;
        
        $session = AttendanceSession::where('id', $id)->where('organization_id', $organizationId)->firstOrFail();
        
        $import = new AttendanceImport($session);
        
        Excel::import($import, $request->file('file'));
        
        $summary = $import->getSummary();
        
        return redirect()->route('attendance.sessions.view', ['tenant_slug' => $tenant_slug,'id' => $id])
                                        ->with('success',"Import Completed: 
                                                        Total: {$summary['total']} | 
                                                        Imported: {$summary['imported']} | 
                                                        Not Found: {$summary['not_found']} | 
                                                        Not Enrolled: {$summary['not_enrolled']} | 
                                                        Invalid Status: {$summary['invalid_status']} |
                                                        Duplicate Entry: {$summary['duplicate_in_file']} | 
                                                        Already Exists: {$summary['already_exists']}"
                                                        );
    }

    
}
