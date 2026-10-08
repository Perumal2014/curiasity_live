<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CourseSetting\Entities\Category;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Lesson;
use App\Models\ScormReport;
use Yajra\DataTables\Facades\DataTables;

class SCORMReportController extends Controller
{

    public function index()
    {
        return view('report.index');
    }

    public function details($id)
    {
        $enroll = CourseEnrolled::with('course.lessons')->find($id);
        $user_id = $enroll->user_id;
        $course = $enroll->course;
        $lessons = $course->lessons->where('host', 'SCORM');

        foreach ($lessons as $lesson) {
            $reports = ScormReport::where('user_id', $user_id)->where('lesson_id', $lesson->id)->get();
            $lesson->reports = $reports;
        }
        return view('report.details', compact('lessons', 'user_id', 'course'));
    }

    public function data()
    {

        $query = CourseEnrolled::select(['course_enrolleds.*'])
            ->with('course', 'user', 'course.lessons');

        $query->whereHas('course', function ($q) {
            return $q->where('type', 1);
        });

        $query->whereHas('course.lessons', function ($q) {
            return $q->where('host', 'SCORM');
        });

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('user.name', function ($query) {
                return $query->user->name;
            })
            ->editColumn('course.title', function ($query) {
                return $query->course->title;
            })
            ->addColumn('lesson', function ($query) {
                return count($query->course->lessons->where('host', 'SCORM'));
            })
            ->addColumn('action', function ($query) {


                return ' <div class="dropdown CRM_dropdown">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                            id="dropdownMenu2" data-bs-toggle="dropdown"
                                                            aria-haspopup="true"
                                                            aria-expanded="false">
                                                        ' . trans('common.Action') . '
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                         aria-labelledby="dropdownMenu2">
                                                         <a class="dropdown-item" href="' . route('scorm.report.details', $query->id) . '" data-id="' . $query->id . '" type="button">' . trans('courses.Lesson List') . '</a>
                                                    </div>
                                                </div>';


            })->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $course_id = $request->course_id;
        $lesson_id = $request->lesson_id;
        $key = $request->key;
        $value = $request->value;
        if ($key == 'cmi.suspend_data') {
            return;
        }
        if (!empty($course_id) && !empty($lesson_id) && !empty($key) && !empty($value)) {
            $setting = ScormReport::where('key', $key)
                ->where('user_id', auth()->id())
                ->where('course_id', $course_id)
                ->where('lesson_id', $lesson_id)
                ->first();
            if ($setting) {
                $setting->value = $value;
                $setting->save();
            } else {
                $setting = new ScormReport();
                $setting->key = $key;
                $setting->value = $value;
                $setting->user_id = auth()->id();
                $setting->course_id = $course_id;
                $setting->lesson_id = $lesson_id;
                $setting->updated_at = now();
                $setting->created_at = now();
                $setting->save();
            }
        }
    }

    public function lessonDetails($user_id, $lesson_id)
    {
        $user = User::FindOrFail($user_id);
        $lesson = Lesson::FindOrFail($lesson_id);
        $reports = ScormReport::where('user_id', $user_id)->where('lesson_id', $lesson_id)->get();
        return view('report.lessons', compact('user', 'reports', 'lesson'));
    }

}
