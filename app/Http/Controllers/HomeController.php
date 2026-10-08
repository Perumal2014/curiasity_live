<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\UserLogin;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\Noticeboard\Entities\Noticeboard;
use Modules\Payment\Entities\Withdraw;
use Modules\Setting\Entities\Badge;
use Modules\Setting\Http\Controllers\BadgeController;
use Modules\Organization\Entities\Organization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\ProfileController;
use App\Models\Tenants;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        //  print_r('test'); exit;
        if (Auth::user()->role_id == 1) {
            return redirect('/home');
        } else if (Auth::user()->role_id == 2) {
            return redirect()->route('tenant.dashboard');
        } else if (Auth::user()->role_id == 3) {
            return redirect()->route('tenant.studentDashboard');
        } else {
            return redirect('/home');
        }
    }

    //dashboard
    public function dashboard()
    {
        try {
            
            $user = Auth::user();
            $slug = session('tenant_slug');
            // print_r($slug); exit;
            
            // Redirect for specific roles
            if ($user->role_id == 3) {
                return redirect()->route('tenant.studentDashboard');
            }

            if (isModuleActive('Affiliate') && $user->role->name == 'Affiliate') {
                return redirect()->route('affiliate.my_affiliate.index');
            }

            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;
            $data = [];

            // Recent enrollments
            $recentEnrollQuery = CourseEnrolled::query()
                ->latest()
                ->take(4)
                ->select('reveune', 'course_id', 'user_id', 'purchase_price')
                ->with('course', 'course.user', 'user');

            if ($user->role_id == 1) {
                $recentEnroll = $recentEnrollQuery->get();

                $coursesEarnings = CourseEnrolled::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->get();

                $courses_enrolle = $coursesEarnings->groupBy(fn($enroll) => $enroll->created_at->format('d M'))
                    ->map(fn($group) => $group->sum('qty'));
            } else {
                $recentEnrollQuery->whereHas('course', fn($query) => $query->where('user_id', $user->id));
                $recentEnroll = $recentEnrollQuery->get();

                $coursesEarnings = CourseEnrolled::whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->whereHas('course', fn($query) => $query->where('user_id', $user->id))
                    ->get();

                $courses_enrolle = $coursesEarnings->groupBy(fn($enroll) => $enroll->created_at->format('d M'))
                    ->map(fn($group) => $group->sum('qty'));
            }

            // Daily revenue
            $dailyRevenue = $coursesEarnings->groupBy(fn($earning) => $earning->created_at->format('d M'))
                ->map(fn($group) => $group->sum('reveune'));

            $courshEarningM_onth_name = $dailyRevenue->keys()->toArray();
            $courshEarningMonthly = $dailyRevenue->values()->toArray();

            // Payment statistics
            $withdrawsQuery = Withdraw::selectRaw('monthname(issueDate) as month, YEAR(issueDate) as year, status')
                ->whereYear('issueDate', $currentYear)
                ->whereMonth('issueDate', $currentMonth);

            if ($user->role_id != 1) {
                $withdrawsQuery->where('instructor_id', $user->id);
            }

            $withdraws = $withdrawsQuery->get();
            $payment_statistics = [
                'paid' => $withdraws->where('status', 1),
                'unpaid' => $withdraws->where('status', 0),
                'month' => Carbon::now()->translatedFormat('F'),
                'year' => translatedNumber(Carbon::now()->year),
            ];

            // Course Overview
            $courseQuery = Course::with('user', 'enrolls');

            if (isModuleActive('Organization') && $user->isOrganization()) {
                $courseQuery->whereHas('user', fn($q) => $q->where('organization_id', Auth::id())->orWhere('id', Auth::id()));
            }
            $allCourses = collect();

            $allCourses = $courseQuery->get();
            $course_overview = [
                'active' => $allCourses->where('status', 1)->count(),
                'pending' => $allCourses->where('status', 0)->count(),
                'courses' => $allCourses->where('type', 1)->count(),
                'quizzes' => $allCourses->where('type', 2)->count(),
                'classes' => $allCourses->where('type', 3)->count(),
            ];

            // Daily Enrollment
            $enroll_day = $courses_enrolle->keys()->toArray();
            $enroll_count = $courses_enrolle->values()->toArray();

            // CPD Module
            $students = isModuleActive('CPD') ? User::select('id','name')->withCount('cpds')->where('role_id', 3)->get() : null;

            // Gamification badges
            $badges = [];

            if (Settings('gamification_status') && Settings('gamification_leaderboard_show_badges_status')) {
                $badgeController = new BadgeController();
                $types = array_keys($badgeController->badgesTypes());
                $myBadgesIds = Auth::user()->userLatestBadges->pluck('badge_id')->toArray();
                $notForStudent = [
                    'blogs',
                    'sales',
                    'rating',
                    'registration'
                ];
                $reg_badges = Badge::select('id', 'point')->where('type', 'registration')->where(function ($query) {
                    $totalDay = 0;
                    if (Auth::check()) {
                        $created = new \Illuminate\Support\Carbon(Auth::user()->created_at);
                        $now = Carbon::now();
                        $totalDay = $now->diffInDays($created);
                    }
                    $query->where('point', '<=', $totalDay);
                })->orderBy('point', 'asc')->get()->pluck('id')->toArray();
                $myBadgesIds = array_merge($myBadgesIds, $reg_badges);
                $badges = Badge::select('title', 'image', 'type', 'point')
                    ->where('status', 1)
                    ->whereIn('type', $types)->where('status', 1)
                    ->whereNotIn('id', $myBadgesIds)
                    ->orderBy('point', 'asc')
                    ->whereIn('type', $notForStudent)
                    ->get()
                    ->groupBy('type');
            }
            // Noticeboard
            if (isModuleActive('Noticeboard') && hasTable('noticeboards')) {
                $courseId = $user->studentCourses->pluck('course_id')->toArray();
                $query = Noticeboard::where('status', 1)->with('noticeType');

                if (isModuleActive('Organization') && !empty($user->organization_id)) {
                    $query->whereHas('user', fn($q) => $q->where('id', $user->organization_id));
                }

                $data['noticeboards'] = $query->whereHas('assign', fn($q) => $q->whereIn('course_id', $courseId)
                    ->orWhere('role_id', $user->role_id))->latest()->limit(5)->get();
            }

            if(Auth::user()->tenant_id == INSTITUTE){
                return view('institute_dashboard');
            } else if (Auth::user()->tenant_id == COLLEGE) {
                return view('college_dashboard');
            }

            // print_r('testss'); exit;

            return view('dashboard', $data, compact(
                'badges',
                'recentEnroll',
                'courshEarningM_onth_name',
                'courshEarningMonthly',
                'payment_statistics',
                'enroll_day',
                'enroll_count',
                'course_overview',
                'allCourses',
                'students'
            ));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function instituteDashboard()
    {
        try {
            $user = Auth::user();
            $slug = session('tenant_slug');

            if ($user->role_id == 3) {
                return redirect()->route('tenant.studentDashboard');
            } else if ($user->role_id == 1) {
                return redirect()->route('tenant.dashboard');
            }


            $organization = Tenants::where('id', Auth::user()->organization_id)->first();

            return view('institute_dashboard', compact('organization'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function sampleDashboard()
    {
        return view('sample_dashboard');
    }

    public function userLoginChartByDays(Request $request)
    {
        $userLoginChartByDays = [];
        $type = $request->type;
        $days = $request->days;

        if ($type == "days") {

            $from = Carbon::now()->subDays($days - 1);
            $to = Carbon::now();


        } else {
            $allDays = explode(' - ', $days);
            $from = Carbon::parse($allDays[0]);
            $to = Carbon::parse($allDays[1]);
        }


        $period = CarbonPeriod::create($from, $to);
        $dates = [];
        $data = [];

        foreach ($period as $key => $value) {
            $day = $value->format('Y-m-d');
            $dates[] = $day;
            $query5 = UserLogin::whereDate('login_at', $day);
            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $query5->whereHas('user', function ($q) {
                    $q->where('organization_id', Auth::id());
                    $q->orWhere('id', Auth::id());
                });
            }
            $data[] = translatedNumber($query5->count());
        }
        $userLoginChartByDays['date'] = $dates;
        $userLoginChartByDays['data'] = $data;

        return $userLoginChartByDays;
    }

    public function userLoginChartByTime(Request $request)
    {
        $userLoginChartByDays = [];
        $type = $request->type;
        $days = $request->days;

        if ($type == "days") {

            $from = Carbon::now()->subDays($days - 1);
            $to = Carbon::now();


        } else {
            $allDays = explode(' - ', $days);
            $from = Carbon::parse($allDays[0]);
            $to = Carbon::parse($allDays[1]);
        }


        $period = CarbonPeriod::create($from, $to);
        $hours = [];

        foreach ($period as $key => $value) {
            $day = $value->format('Y-m-d');


            $query6 = UserLogin::whereDate('login_at', $day);

            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $query6->whereHas('user', function ($q) {
                    $q->where('organization_id', Auth::id());
                    $q->orWhere('id', Auth::id());
                });
            }

            $loginData = $query6->get(['id', 'login_at'])->groupBy(function ($date) {
                return Carbon::parse($date->login_at)->format('H');
            });

            for ($i = 0; $i <= 23; $i++) {
                if (!isset($hours[$i])) {
                    $hours[$i] = 0;
                }
                if (!isset($loginData[$i])) {
                    $loginData[$i] = [];
                }
                $hours[$i] = count($loginData[$i]) + $hours[$i];
            }
        }
        return $hours;
    }

    public function getDashboardData(Request $request)
    {
        try {
            //return 'test';
            $type = $request->get('type', '7');

            $startDate = null;
            $endDate = null;

            if ($type == '7') {
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
            } elseif ($type == '30') {
                $startDate = Carbon::now()->subDays(30)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
            } elseif ($type == 'custom') {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $endDate = Carbon::parse($request->end_date)->endOfDay();
            }
            $tenant_type = session('tenant_type');
            $org_id = session('tenant_id');
            
            if ($tenant_type == CORPORATE) {
                $info['student'] = translatedNumber(User::where('role_id', 3)->where('organization_id', $org_id)->count());
                $info['totalCourses'] = translatedNumber(Course::where('organization_id', $org_id)->count());
                $info['totalEnrolled'] = translatedNumber(CourseEnrolled::whereHas('course', function ($q) use ($org_id) {
                                            $q->where('organization_id', $org_id);
                                            })->count());
                $categoryReports        = DB::table('categories')
                                        ->leftJoin('courses', 'courses.category_id', '=', 'categories.id')
                                        ->leftJoin('course_enrolleds', 'course_enrolleds.course_id', '=', 'courses.id')

                                        ->select(
                                            'categories.id',
                                            'categories.name as category_name',

                                            DB::raw('COUNT(DISTINCT course_enrolleds.user_id) as unique_reach'),

                                            DB::raw('COUNT(course_enrolleds.id) as total_reaches'),

                                            DB::raw('COUNT(course_enrolleds.id) as total_enrolled'),

                                            DB::raw('0 as total_time_spent'),

                                            DB::raw('0 as completion_rate')
                                        )->whereNull('categories.parent_id')

                                        ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                                            $q->whereBetween(
                                                'course_enrolleds.created_at',
                                                [$startDate, $endDate]
                                            );
                                        })->groupBy(
                                            'categories.id',
                                            'categories.name'
                                        )->get();
                $managementReports = DB::table('management')
                                    ->leftJoin('users', 'users.mgt_id', '=', 'management.id')
                                    ->leftJoin('course_enrolleds', 'course_enrolleds.user_id', '=', 'users.id')
                                    ->select(
                                        'management.id',
                                        'management.management_name as management_name',

                                        DB::raw('COUNT(DISTINCT course_enrolleds.user_id) as unique_reach'),

                                        DB::raw('COUNT(course_enrolleds.id) as total_reaches'),

                                        DB::raw('COUNT(course_enrolleds.id) as total_enrolled'),

                                        DB::raw('0 as total_time_spent'),

                                        DB::raw('0 as completion_rate')
                                    )->groupBy('management.id','management.management_name')->get();
                
                $countryReports     = DB::table('associate_country')
                                        ->leftJoin('users', 'users.country', '=', 'associate_country.id')
                                        ->leftJoin('course_enrolleds', 'course_enrolleds.user_id', '=', 'users.id')
                                        ->select(
                                            'associate_country.id',
                                            'associate_country.country_name as country_name',

                                        DB::raw('COUNT(DISTINCT course_enrolleds.user_id) as unique_reach'),

                                        DB::raw('COUNT(course_enrolleds.id) as total_reaches'),

                                        DB::raw('COUNT(course_enrolleds.id) as total_enrolled'),

                                        DB::raw('0 as total_time_spent'),

                                        DB::raw('0 as completion_rate')
                                    )->groupBy('associate_country.id', 'associate_country.country_name')->get();

                    $buReports     = DB::table('business_unit')
                                        ->leftJoin('users', 'users.bu', '=', 'business_unit.id')
                                        ->leftJoin('course_enrolleds', 'course_enrolleds.user_id', '=', 'users.id')
                                        ->select(
                                            'business_unit.id',
                                            'business_unit.bu_name as bu_name',

                                        DB::raw('COUNT(DISTINCT course_enrolleds.user_id) as unique_reach'),

                                        DB::raw('COUNT(course_enrolleds.id) as total_reaches'),

                                        DB::raw('COUNT(course_enrolleds.id) as total_enrolled'),

                                        DB::raw('0 as total_time_spent'),

                                        DB::raw('0 as completion_rate')
                                    )->groupBy('business_unit.id', 'business_unit.bu_name')->get();
               
               
                                    // dd($buReports->toSql());
                $info['categoryReports'] = $categoryReports;
                $info['managementReports'] = $managementReports;
                $info['countryReports'] = $countryReports;
                $info['buReports'] = $buReports;

                // print_r($info['buReports']); exit;

                return response()->json($info);
            }
            $user  = Auth::user();
            $staff = $user->staffDetail;

           $query = User::where('role_id', roles('student'))
                    ->when($user->role_id != 1, function ($q) use ($user) {
                        $q->where('organization_id', $user->organization_id);
                    });
            /**
             * ROLE BASED FILTERS
             *
             * role 7 → no dept, no year
             * role 4 → dept only
             * role 9 → dept + year
             */
            if ($staff) {

                // role 4 & 9 → department
                if (in_array($user->role_id, [4, 9])) {
                    $query->whereHas('studDetail', function ($q) use ($staff) {
                        $q->where('department_id', $staff->department_id);
                    });
                }

                // role 9 → year
                if ($user->role_id == 9) {
                    $query->whereHas('studDetail', function ($q) use ($staff) {
                        $q->where('student_year', $staff->handle_year);
                    });
                }
            }
            
            $info['student'] = $query->count();

            return response()->json($info);


            // $info['student'] = array('role_id'        => $user->role_id,
            //     'role_id'        => $user->role_id,
            //     'organization'   => $user->organization_id,
            //     'staff_exists'   => (bool) $staff,
            //     'department_id'  => optional($staff)->department_id,
            //     'handle_year'    => optional($staff)->handle_year,
            // );
            //$info['student'] = $query->count();

          //  return response()->json($info);

    
            if ($user->role_id == 2) {
                $allCourseEnrolled = CourseEnrolled::with('user', 'course')
                    ->whereHas('course', function ($query) use ($user) {
                        $query->where('user_id', '=', $user->id);
                    })->get();

                $allCourses = Course::where('user_id', $user->id)->get();

                $thisMonthEnroll = CourseEnrolled::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->format('m'))
                    ->whereHas('course', function ($query) use ($user) {
                        $query->where('user_id', '=', $user->id);
                    })->sum('purchase_price');


                $today = CourseEnrolled::whereDate('created_at', Carbon::today())
                    ->whereHas('course', function ($query) use ($user) {
                        $query->where('user_id', '=', $user->id);
                    })->sum('purchase_price');


                $rev = $allCourseEnrolled->sum('reveune');
            } else if ($user->role_id == 9) {
                $student = User::where('role_id', 3)->where('organization_id', $user->organization_id)->first();
            } else {
                $query = CourseEnrolled::query();
                if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                    $query->whereHas('course.user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                }
                $allCourseEnrolled = $query->get();
                $query2 = Course::query();
                if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                    $query2->whereHas('user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                }
                $allCourses = collect();
                $allCourses = $query2->get();

                $query3 = CourseEnrolled::whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->format('m'));

                if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                    $query3->whereHas('course.user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                }

                $thisMonthEnroll = $query3->sum('purchase_price');

                $query4 = CourseEnrolled::whereDate('created_at', Carbon::today());
                if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                    $query4->whereHas('course.user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                }
                $today = $query4->sum('purchase_price');

                //
                $rev = (isModuleActive('Organization') && Auth::user()->isOrganization()) ? $allCourseEnrolled->sum('reveune') : $allCourseEnrolled->sum('purchase_price') - $allCourseEnrolled->sum('reveune');
            }

            $info['allCourse'] = translatedNumber($allCourses->count());
            $info['totalEnroll'] = translatedNumber($allCourseEnrolled->count());
            $info['thisMonthEnroll'] = getPriceFormat($thisMonthEnroll,false);
            $info['today'] = getPriceFormat($today,false);

            $user_query = User::whereIn('role_id', [2, 3]);

            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $user_query->where('organization_id', Auth::id());
            }
            $users = $user_query->get();

            
            $info['student'] = translatedNumber($users->where('role_id', 3)->count());
            $info['instructor'] = translatedNumber($users->where('role_id', 2)->count());
            $info['totalSell'] = getPriceFormat($allCourseEnrolled->sum('purchase_price'),false);
            $info['adminRev'] = getPriceFormat($rev,false);
            //print_r($info); exit;
            return Response::json($info);
        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()]);
        }
    }

    public function getInstituteData(Request $request)
    {
        try {
            $tenantId = Auth::user()->organization_id;

            return response()->json([
                'students_count' => User::where('organization_id',$tenantId)
                                ->where('role_id',3)
                                ->count(),

                'courses_count' => Course::where('organization_id',$tenantId)->count(),

                'instructors_count' => User::where('organization_id',$tenantId)
                                    ->whereIn('role_id',[10])
                                    ->count(),

                'enrolled_count' => CourseEnrolled::whereHas('user', function ($query) use ($tenantId) {
                                        $query->where('organization_id', $tenantId);
                                    })->count(),

                'monthly' => $this->monthlyEnrollment($tenantId),

                'top_courses' => $this->topCourses($tenantId),

                'activities' => $this->recentActivities($tenantId),

                'approvals' => $this->pendingApprovals($tenantId),

                'categories' => $this->courseDistribution($tenantId),
            ]);

        } catch (Exception $e) {
            return Response::json(['error' => $e->getMessage()]);
        }
    }
    

    public function monthlyEnrollment($tenantId)
    {
       $monthlyEnrollments = CourseEnrolled::join('users', 'users.id', '=', 'course_enrolleds.user_id')
        ->selectRaw('MONTH(course_enrolleds.created_at) as month, COUNT(*) as count')
        ->where('users.organization_id', $tenantId)
        ->whereYear('course_enrolleds.created_at', date('Y')) // Optional: current year
        ->groupByRaw('MONTH(course_enrolleds.created_at)')
        ->orderByRaw('MONTH(course_enrolleds.created_at)')
        ->get();

        return $monthlyEnrollments;
    }

    public function topCourses($tenantId)
    {
       $topCourses = Course::where('organization_id', $tenantId)
                    ->with('user:id,name', 'category:id,title')
                    ->withCount('enrolls')
                    ->take(5)
                    ->get()
                    ->map(function ($course) {

                        return [
                            'title' => $course->title, // or $course->getTitleAttribute()
                            'category' => optional($course->category)->title ?? '-',
                            'instructor' => optional($course->user)->name ?? '-',
                            'students' => $course->enrolls_count,
                            'completion' => 0,
                            'status' => 'Live',
                        ];
                    });

        return $topCourses;
    }

    public function getCourseTitle($title)
    {
        $maxLength = 30; // Set your desired maximum length
        if (strlen($title) > $maxLength) {
            return substr($title, 0, $maxLength) . '...';
        }
        return $title;
    }   

    public function recentActivities($tenantId)
    {
        $recentActivities = CourseEnrolled::select(
        'course_enrolleds.*',
        'users.name as user_name',
        'courses.title as course_title'
        )
        ->join('users', 'users.id', '=', 'course_enrolleds.user_id')
        ->join('courses', 'courses.id', '=', 'course_enrolleds.course_id')
        ->where('users.organization_id', $tenantId)
        ->latest('course_enrolleds.created_at')
        ->limit(5)
        ->get();

        return $recentActivities;
    }

    public function pendingApprovals($tenantId)
    {
        $pendingApprovals = Course::where('organization_id', $tenantId)
            ->where('status', 0) // Assuming 0 is the status for pending approval
            ->count();

        return $pendingApprovals;
    }

    public function courseDistribution($tenantId)
    {
        return Course::with('category:id,name')
            ->where('organization_id', $tenantId)
            ->select('category_id', DB::raw('COUNT(*) as count'))
            ->groupBy('category_id')
            ->get()
            ->map(function ($course) {

                return [
                    'category_id' => $course->category_id,
                    'category_name' => is_array($course->category->name)
                                        ? ($course->category->name['en'] ?? '')
                                        : (json_decode($course->category->name, true)['en'] ?? $course->category->name),
                    'count'       => $course->count,
                ];
            });
    }


    public function validateGenerate()
    {
        return view('validate_generate');
    }


    public function validateGenerateSubmit()
    {
        $field = request()->field;
        $rules = request()->rules;
        $arr = [];


        $single_rule = explode('|', $rules);


        foreach ($single_rule as $rule) {
            $string = explode(':', $rule);
            $rule_name = $rule_message_key = $string[0];

            if (in_array($rule_name, ['max', 'min'])) {
                $rule_message_key = $rule_message_key . '.string';
            }

            $message = __('validation.' . $rule_message_key);

            $field_string = str_replace('_', ' ', $field);

            $message = str_replace(
                [':attribute', ':ATTRIBUTE', ':Attribute'],
                [$field_string, Str::upper($field_string), Str::ucfirst($field_string)],
                $message
            );
            if (in_array($rule_name, ['max', 'min'])) {
                $message = str_replace(
                    [':' . $rule_name],
                    [$string[1]],
                    $message
                );
            }

            if ($rule_name == 'required_if') {
                $ex = explode(',', $string[1]);
                $message = str_replace(
                    [':other'],
                    [str_replace('_', ' ', $ex[0])],
                    $message
                );
                if (isset($ex[2])) {
                    $message = str_replace(
                        [':value', "'"],
                        [str_replace('_', ' ', $ex[2]), ''],
                        $message
                    );
                } else {
                    $message = str_replace(
                        [':value', "'"],
                        [str_replace('_', ' ', $ex[1]), ''],
                        $message
                    );
                }
            }

            if ($rule_name == 'mimes') {

                $message = str_replace(
                    [':values'],
                    [str_replace('_', ' ', $string[1])],
                    $message
                );
            }
            if ($rule_name == 'same') {

                $message = str_replace(
                    [':other'],
                    [str_replace('_', ' ', $string[1])],
                    $message
                );
            }
            if ($rule_name == 'required_with') {

                $message = str_replace(
                    [':values'],
                    [str_replace('_', ' ', $string[1])],
                    $message
                );
            }

            if ($rule_name == 'after_or_equal') {

                $message = str_replace(
                    [':date'],
                    [str_replace('_', ' ', $string[1])],
                    $message
                );
            }
            if ($rule_name == 'after') {

                $message = str_replace(
                    [':date'],
                    [str_replace('_', ' ', $string[1])],
                    $message
                );
            }


            $arr [$field . '.' . $rule_name] = $message;
        }

        $defaultFile = public_path('/../resources/lang/default/validation.php');
        $languages = include "{$defaultFile}";
        $languages = array_merge($languages, $arr);
        file_put_contents($defaultFile, '');
        file_put_contents($defaultFile, '<?php return ' . var_export($languages, true) . ';');

        return view('validate_generate', compact('field', 'rules', 'arr'));
    }

    public function collegeDashboard()
    {
        $organization_id = Auth::user()->organization_id;

        $courses = Course::where('organization_id', $organization_id)
            ->where('status', 1)
            ->select('id', 'title', 'type')
            ->orderBy('id')
            ->get();

        return view('coursesetting::college_dashboard', compact('courses'));
    }

    public function getCollegeData()
    {
        $totalStudents = User::where('organization_id', Auth::user()->organization_id)->count();

        $activeCourses = Course::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)
            ->count();

        $facultyMembers = User::where('organization_id', Auth::user()->organization_id)
            ->where('status', 1)
            ->whereIn('role_id', ['4','9'])
            ->count();

        $totalEnrollments = Course::with('enrollUsers')->where('organization_id', Auth::user()->organization_id)->count();
            
        /*
        |--------------------------------------------------------------------------
        | Enrollment Trend
        |--------------------------------------------------------------------------
        */

        $enrollmentTrend = [];

        for ($month = 1; $month <= 12; $month++) {

            $count = Course::where('organization_id', $organizationId)
                ->whereHas('enrollUsers', function ($query) use ($month) {
                    $query->whereMonth('created_at', $month)
                        ->whereYear('created_at', now()->year);
                })
                ->withCount([
                    'enrollUsers as enrollment_count' => function ($query) use ($month) {
                        $query->whereMonth('created_at', $month)
                            ->whereYear('created_at', now()->year);
                    }
                ])
                ->get()
                ->sum('enrollment_count');

            $enrollmentTrend[] = $count;
        }

        
        return json_encode([
            'totalStudents' => $totalStudents,
            'activeCourses' => $activeCourses,
            'facultyMembers' => $facultyMembers,
            'totalEnrollments' => $totalEnrollments,
            'enrollmentCount' => array_sum($enrollmentTrend),
            'enrollmentTrend'  => $enrollmentTrend,

        ]);
       

    }
}
