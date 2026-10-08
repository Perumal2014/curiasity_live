<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\CourseSetting\Entities\Lesson;
use Modules\CourseSetting\Entities\Chapter;
use App\Models\Profile;
use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\Management;
use App\Models\AssociateState;
use App\Models\AssociateCity;
use App\Models\LocationCode;
use App\Models\AssociateCountry;
use App\Models\UserLearningActivity;
use App\LessonComplete;

class LMSExport implements FromCollection,  WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        $headings = [
            'Course Title',
            'Enrollment Date',
            'Instructor',
            'UserName',
            'Email',
            'Phone',
            'Profile',
            'Business Unit',
            'Department',
            'Country',
            'State',
            'City',
            'Location',
            'Management',
        ];

        if ($this->request->lesson_wise == '1') {
            $headings[] = 'Module Name';
            $headings[] = 'Lesson Name';
        } else if ($this->request->module_wise == '1') {
            $headings[] = 'Module Name';
        }

        $headings[] = 'Total Attempts';
        $headings[] = 'Spend Time';
        $headings[] = 'Status';

        return $headings;
    }

    public function collection()
    {
        $course_id = $this->request->get('course_id');
        $group_id  = $this->request->get('group_id');
        $module_wise  = $this->request->get('module_wise');
        $lesson_wise = $this->request->get('lesson_wise');

        $query = CourseEnrolled::with('course', 'user', 'course.user');

        // ✅ Organization filter
        $query->whereHas('course', function ($q) {
            $q->where('organization_id', Auth::user()->organization_id);
        });

        // ✅ Course filter
        if (!empty($course_id)) {
            $query->where('course_id', $course_id);
        }

        // ✅ Group filter (FIXED)
        if (!empty($group_id)) {

            $parts = explode('_', $group_id);

            if (count($parts) == 2) {
                [$type, $id] = $parts;

                $columnMap = [
                    'profile'  => 'profile_id',
                    'bu'       => 'bu',
                    'dept'     => 'dept_id',
                    'country'  => 'country',
                    'state'    => 'state',
                    'city'     => 'city',
                    'location' => 'location_code',
                    'manager'  => 'mgt_id',
                ];

                if (isset($columnMap[$type])) {

                    $column = $columnMap[$type];

                    // ✅ Get matching users
                    $userIds = User::where($column, $id)->pluck('id');

                    $query->whereIn('user_id', $userIds);
                }
            }
        }

        $enrolls = $query->latest()->get();

        // print_r($enrolls); // Debugging line to check if enrollments are being retrieved
        // exit;    

        $rows = [];

        foreach ($enrolls as $item) {

    $user = $item->user;

    if (!$user) {
        continue;
    }

    $bu_detail = BusinessUnit::find($user->bu);
    $profile = Profile::find($user->profile_id);
    $department = Department::find($user->dept_id);
    $country = AssociateCountry::find($user->country);
    $state = AssociateState::find($user->state);
    $city = AssociateCity::find($user->city);
    $location = LocationCode::find($user->location_code);
    $management = Management::find($user->mgt_id);

    // ==========================
    // LESSON WISE EXPORT
    // ==========================
    if ($this->request->lesson_wise == '1') {

        $lessons = Lesson::with('chapterwise')
            ->where('course_id', $item->course_id)
            ->get();

        foreach ($lessons as $lesson) {
            $difference = 0;
            $attempts = 0;
            $totalSeconds = 0;
            
                $attempts = UserLearningActivity::where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->count();
    
                $activities = UserLearningActivity::where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->get();
            $lessonComplete = LessonComplete::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            

            $status = ($lessonComplete && $lessonComplete->status == 1) ? 'Completed' : 'In Progress';

            foreach ($activities as $activity) {

                if ($activity->start_time && $activity->end_time) {

                    $start = \Carbon\Carbon::parse($activity->start_time);
                    $end = \Carbon\Carbon::parse($activity->end_time);

                    $totalSeconds += $start->diffInSeconds($end);
                }
            }
            
            $spentTime = gmdate('H:i:s', $totalSeconds);

            $rows[] = [
                'Course Title'    => optional($item->course)->title,
                'Enrollment Date' => optional($item->created_at)->format('Y-m-d H:i:s'),
                'Instructor'      => optional(optional($item->course)->user)->name,
                'UserName'        => $user->name,
                'Email'           => $user->email,
                'Phone'           => $user->phone,
                'Profile'         => $profile ? $profile->profile_name : '-',
                'Business Unit'   => $bu_detail ? $bu_detail->bu_name : '-',
                'Department'      => $department ? $department->dept_name : '-',
                'Country'         => $country ? $country->country_name : '-',
                'State'           => $state ? $state->state_name : '-',
                'City'            => $city ? $city->city_name : '-',
                'Location'        => $location ? $location->location_code : '-',
                'Management'      => $management ? $management->management_name : '-',
                'Module Name'     => optional($lesson->chapterwise)->name ?? '-',
                'Lesson Name'     => $lesson->name ?? '-',
                'Total Attempts'  => $attempts ?? 0,
                'Spend Time'      => $spentTime ?? '00:00:00',
                'Status'            => $status ?? 'In Progress',
            ];
        }
    }

    // ==========================
    // MODULE WISE EXPORT
    // ==========================
    elseif ($this->request->module_wise == '1') {

        $chapters = Chapter::where('course_id', $item->course_id)->get();

        $lessons = Lesson::where('course_id', $item->course_id)->get();

        // print_r($lessons); // Debugging line to check if lessons are being retrieved
        // exit;

        $totalAttempts = 0;
        $totalSeconds = 0;
        $completedLessons = 0;
        $lessonStatus = 'In Progress';

        foreach ($lessons as $lesson) {

            $lessonComplete = LessonComplete::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            $lessonStatus = ($lessonComplete && $lessonComplete->status == 1) ? 'Completed' : 'In Progress';

            $attempts = UserLearningActivity::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->count();


            $totalAttempts += $attempts;

            if ($lessonComplete && $lessonComplete->status == 1) {
                $completedLessons++;
            }

            $activities = UserLearningActivity::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->get();

            

            foreach ($activities as $activity) {

                if ($activity->start_time && $activity->end_time) {

                    $start = \Carbon\Carbon::parse($activity->start_time);
                    $end = \Carbon\Carbon::parse($activity->end_time);

                    $totalSeconds += $start->diffInSeconds($end);

                    
                }
            }
        }

        $spentTime = gmdate('H:i:s', $totalSeconds);


        $courseStatus = $lessonStatus;


        foreach ($chapters as $chapter) {

            $rows[] = [
                'Course Title'    => optional($item->course)->title,
                'Enrollment Date' => optional($item->created_at)->format('Y-m-d H:i:s'),
                'Instructor'      => optional(optional($item->course)->user)->name,
                'UserName'        => $user->name,
                'Email'           => $user->email,
                'Phone'           => $user->phone,
                'Profile'         => $profile ? $profile->profile_name : '-',
                'Business Unit'   => $bu_detail ? $bu_detail->bu_name : '-',
                'Department'      => $department ? $department->dept_name : '-',
                'Country'         => $country ? $country->country_name : '-',
                'State'           => $state ? $state->state_name : '-',
                'City'            => $city ? $city->city_name : '-',
                'Location'        => $location ? $location->location_code : '-',
                'Management'      => $management ? $management->management_name : '-',
                'Module Name'     => $chapter->name ?? '-',
                'Total Attempts'  => $totalAttempts ?? 0,
                'Spend Time'      => $spentTime ?? '00:00:00',
                'Status'           => $lessonStatus ?? 'In Progress',
            ];
        }
    }

    // ==========================
    // NORMAL EXPORT
    // ==========================
    else {

        $lessons = Lesson::where('course_id', $item->course_id)->get();

        // print_r($lessons); // Debugging line to check if lessons are being retrieved
        // exit;

        $totalAttempts = 0;
        $totalSeconds = 0;
        $completedLessons = 0;
        $lessonStatus = 'In Progress';

        foreach ($lessons as $lesson) {

            $lessonComplete = LessonComplete::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            $lessonStatus = ($lessonComplete && $lessonComplete->status == 1) ? 'Completed' : 'In Progress';

            $attempts = UserLearningActivity::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->count();


            $totalAttempts += $attempts;

            if ($lessonComplete && $lessonComplete->status == 1) {
                $completedLessons++;
            }

            $activities = UserLearningActivity::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->get();

            

            foreach ($activities as $activity) {

                if ($activity->start_time && $activity->end_time) {

                    $start = \Carbon\Carbon::parse($activity->start_time);
                    $end = \Carbon\Carbon::parse($activity->end_time);

                    $totalSeconds += $start->diffInSeconds($end);

                    
                }
            }
        }

        $spentTime = gmdate('H:i:s', $totalSeconds);


        $courseStatus = $lessonStatus;


        $rows[] = [
            'Course Title'    => optional($item->course)->title,
            'Enrollment Date' => optional($item->created_at)->format('Y-m-d H:i:s'),
            'Instructor'      => optional(optional($item->course)->user)->name,
            'UserName'        => $user->name,
            'Email'           => $user->email,
            'Phone'           => $user->phone,
            'Profile'         => $profile ? $profile->profile_name : '-',
            'Business Unit'   => $bu_detail ? $bu_detail->bu_name : '-',
            'Department'      => $department ? $department->dept_name : '-',
            'Country'         => $country ? $country->country_name : '-',
            'State'           => $state ? $state->state_name : '-',
            'City'            => $city ? $city->city_name : '-',
            'Location'        => $location ? $location->location_code : '-',
            'Management'      => $management ? $management->management_name : '-',
            'Total Attempts'  => $attempts ?? 0,
            'Spend Time'      => $spentTime ?? '00:00:00',
            'Status'           => $courseStatus ?? 'In Progress',
        ];
    }
}

        return collect($rows);
    }
}