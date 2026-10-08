<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebsiteController;
use App\Traits\SendNotification;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Certificate\Entities\Certificate;
use Modules\Certificate\Entities\CertificateRecord;
use Modules\Certificate\Http\Controllers\CertificateController;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseCanceled;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\Payment\Entities\InstructorPayout;
use Modules\Payment\Entities\InstructorTotalPayout;
use Modules\Payment\Entities\Withdraw;
use Modules\StudentSetting\Entities\Institute;
use Modules\Subscription\Entities\SubscriptionCheckout;
use Modules\Subscription\Entities\SubscriptionCourse;
use Yajra\DataTables\DataTables;
use App\Models\CourseApproval;
use Modules\Payment\Entities\Cart;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LMSExport;
use App\Models\BusinessUnit;
use App\Models\AssociateCity;
use App\Models\AssociateCountry;
use App\Models\AssociateState;
use App\Models\Profile;
use App\Models\LocationCode;
use App\Models\Management;
use App\Models\Department;
use Modules\CourseSetting\Entities\Lesson;


class AdminController extends Controller
{
    use SendNotification;

    public function enrollLogs(Request $request)
    {
        $courseId = $request->get('course', '');
        $start = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';

        try {
        //     $enrolls = [];
        //     $course_query = Course::where('status', 1)
        //         ->whereIn('type', [1,2,3]);

        //     if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
        //         $course_query->whereHas('user', function ($q) {
        //             $q->where('organization_id', Auth::id());
        //             $q->orWhere('id', Auth::id());
        //         });
        //     }

        //     $courses = $course_query->select('id', 'title', 'type')->get();

        //     $query = User::where('role_id', 3);
        //     if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
        //         $query->where('organization_id', Auth::id());
        //         $query->orWhere('id', Auth::id());
        //     }
            
        //     if (Auth::user()->role_id == 9) {

        //         $staff = Auth::user()->staffDetail;

        //         if ($staff) {
        //             $query->where('organization_id', Auth::user()->organization_id)
        //                 ->whereHas('studDetail', function ($q) use ($staff) {
        //                     $q->where('department_id', $staff->department_id)
        //                         ->where('student_year', $staff->handle_year);
        //                 });
        //         }
        //         //  dd(['sql' => $query->toSql(),'bindings' => $query->getBindings(),]);
        //     }
        //     $students = $query->get();
            $students = null;
            $enrolls = [];
            $courses = [];

           // print_r($students); exit;
            return view('backend.student.enroll_student', compact('courseId', 'start', 'end', 'enrolls', 'courses', 'students'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed', $e), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function approveLogs(Request $request)
    {
        $courseId = $request->get('course', '');
        $start = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';

        try {
            $students = null;
            $enrolls = [];
            $courses = [];

           // print_r($students); exit;
            return view('backend.student.enroll_approve', compact('courseId', 'start', 'end', 'enrolls', 'courses', 'students'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed', $e), trans('common.Failed'));
            return redirect()->back();
        }
    }
    

    public function getApproveLogsData(Request $request)
    {
        $user = Auth::user();

        $query = CourseApproval::select(
            'course_approvals.id',
            'course_approvals.course_id',
            'course_approvals.status',
            'course_approvals.user_id',
            'courses.title as course_title',
            'users.name as user_name',
            'users.image as user_image',
            'users.reporting_manager_id',
            'profile.profile_name'
        )
        ->leftJoin('courses', 'courses.id', '=', 'course_approvals.course_id')
        ->leftJoin('users', 'users.id', '=', 'course_approvals.user_id')
        ->leftJoin('profile', 'profile.id', '=', 'users.profile_id')
        ->where('course_approvals.status', 0);

        if ($user->role_id == 11) {
            $query->where('users.organization_id', $user->organization_id);
        }
        elseif ($user->role_id == 13) {
            $query->where('course_approvals.approved_by', $user->id);
        }
        else {
            $query->whereRaw('1 = 0');
        }

        if (!empty($request->course)) {
            $query->where('course_approvals.course_id', $request->course);
        }

        if (!empty($request->start_date)) {
            $query->whereDate('course_approvals.created_at', '>=', $request->start_date);
        }

        if (!empty($request->end_date)) {
            $query->whereDate('course_approvals.created_at', '<=', $request->end_date);
        }

        return Datatables::of($query)

            ->addIndexColumn()

            ->addColumn('checkbox', function ($query) {
                return view('backend.student._td_bulk_approve_checkbox', compact('query'));
            })

            ->addColumn('course_name', function ($query) {

                $image = $query->user_image;
                $user = $query->user_name;

                return view(
                    'backend.partials._small_profile_image',
                    compact('image', 'user')
                );
            })

            ->editColumn('course_title', function ($query) {

                $title = json_decode($query->course_title, true);

                $lang = app()->getLocale();

                return $title[$lang] ?? $title['en'] ?? '-';
            })

           ->editColumn('status', function ($query) {

                return view('backend.student._td_approve_status', compact('query'));
            })

            ->addColumn('action', function ($query) {
                return view('backend.student._td_approve_log', compact('query'));
            })

            ->rawColumns([
                'checkbox',
                'course_name',
                'status',
                'action'
            ])

            ->make(true);
    }

    public function getEnrollLogsData(Request $request)
    {
        $user = Auth::user();
        $query = CourseEnrolled::select('course_enrolleds.*')->with('user:id,name,image,email', 'course:id,title', 'course.enrolls:id', 'course.certificate_records');

        
        if ($user->role_id == 3) {
            $query->whereHas('course', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            });

        } else  if ($user->role_id == 7 || $user->role_id == 11 || $user->role_id == 10) {
            $query->whereHas('user', function ($f) use ($user) {
                $f->where('organization_id', '=', $user->organization_id);
            });

        } else if ($user->role_id == 9) {

                $staff = $user->staffDetail;

                if ($staff) {
                    $query->whereHas('user', function ($q) use ($user, $staff) {
                        $q->where('organization_id', $user->organization_id)
                        ->whereHas('studDetail', function ($q2) use ($staff) {
                            $q2->where('department_id', $staff->department_id)
                                ->where('student_year', $staff->handle_year);
                        });
                    });
                } 
            
            } else if ($user->role_id == 4) {
                $staff = $user->staffDetail;

                if ($staff) {
                    $query->whereHas('user', function ($q) use ($user, $staff) {
                        $q->where('organization_id', $user->organization_id)
                        ->whereHas('studDetail', function ($q2) use ($staff) {
                            $q2->where('department_id', $staff->department_id);
                        });
                    });
                }
            }
            
                else if ($user->role_id == 8) {
                 $query->whereHas('user', function ($q) use ($user) {
                            $q->where('organization_id', $user->organization_id);
                        });
            }   else {
                if (isModuleActive('Organization') && Auth::user()->isOrganization()) {

                $query->where(function ($query){
                    $query->whereHas('user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    })->orWhereHas('course.user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                });
            }
        } 

        
        if (!empty($request->course)) {
            $query->where('course_id', $request->course);
        }
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        // $query->whereHas('user');

        // print_r($query->toSql()); exit;

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($query) {
                return view('backend.student._td_bulk_checkbox', compact('query'));
            })
            ->addColumn('image', function ($query) {
                $image = $query->user->image;
                $user = $query->user->name;
                return view('backend.partials._small_profile_image', compact('image', 'user'));
            })->editColumn('user.name', function ($query) {
                return $query->user->name;

            })->editColumn('user.email', function ($query) {
                return $query->user->email;

            })
            ->editColumn('course.title', function ($query) {
                return $query->course->title;

            })
            ->editColumn('created_at', function ($query) {
                return showDate(@$query->created_at);

            })->editColumn('purchase_price', function ($query) {
                return getPriceFormat(@$query->purchase_price);

            })
            ->addColumn('action', function ($query) {

                return view('backend.student._td_enroll_log', compact('query'));

            })->rawColumns(['image', 'action'])->make(true);
    }

    public function getStudentsLogsData(Request $request)
    {
        $user = Auth::user();
        $query = CourseEnrolled::select('course_enrolleds.*')->with('user:id,name,image,email', 'course:id,title', 'course.enrolls:id', 'course.certificate_records');

        
        if ($user->role_id == 2) {
            $query->whereHas('course', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            });

        } else if ($user->role_id == 9) {

                $staff = $user->staffDetail;

                if ($staff) {
                    $query->whereHas('user', function ($q) use ($user, $staff) {
                        $q->where('organization_id', $user->organization_id)
                        ->whereHas('studDetail', function ($q2) use ($staff) {
                            $q2->where('department_id', $staff->department_id)
                                ->where('student_year', $staff->handle_year);
                        });
                    });
                }
            } else if ($user->role_id == 8) {
                 $query->whereHas('user', function ($q) use ($user) {
                            $q->where('organization_id', $user->organization_id);
                        });
            }   else {
                if (isModuleActive('Organization') && Auth::user()->isOrganization()) {

                $query->where(function ($query){
                    $query->whereHas('user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    })->orWhereHas('course.user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                });
            }
        } 

        
        if (!empty($request->course)) {
            $query->where('course_id', $request->course);
        }
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        $query->whereHas('user');

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($query) {
                return view('backend.student._td_bulk_checkbox', compact('query'));
            })
            ->addColumn('image', function ($query) {
                $image = $query->user->image;
                $user = $query->user->name;
                return view('backend.partials._small_profile_image', compact('image', 'user'));
            })->editColumn('user.name', function ($query) {
                return $query->user->name;

            })->editColumn('user.email', function ($query) {
                return $query->user->email;

            })
            ->editColumn('course.title', function ($query) {
                return $query->course->title;

            })
            ->editColumn('created_at', function ($query) {
                return showDate(@$query->created_at);

            })->editColumn('purchase_price', function ($query) {
                return getPriceFormat(@$query->purchase_price);

            })
            ->addColumn('action', function ($query) {

                return view('backend.student._td_enroll_log', compact('query'));

            })->rawColumns(['image', 'action'])->make(true);
    }
    

    public function cancelLogs(Request $request)
    {
        $courseId = $request->get('course', '');
        $start = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';

        try {
            $enrolls = [];
            $course_query = Course::where('status', 1)->whereIn('type', [1,2,3]);

            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $course_query->whereHas('user', function ($q) {
                    $q->where('organization_id', Auth::id());
                    $q->orWhere('id', Auth::id());
                });
            }

            $courses = $course_query->select('id', 'title', 'type')->get();

            $query = User::where('role_id', 3);
            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $query->where('organization_id', Auth::id());
                $query->orWhere('id', Auth::id());
            }
            $students = $query->get();
            return view('backend.student.cancel_student', compact('courseId', 'start', 'end', 'enrolls', 'courses', 'students'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function enrollFilter(Request $request)
    {
        try {
            //print_r('test'); exit;
            $courseId = $request->get('course', '');
            $start = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
            $end = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';


            $courses = Course::query()->whereIn('type', [1,2,3])->get();
            $query = User::where('role_id', 3);
            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $query->where('organization_id', Auth::id());
            }
            if (Auth::user()->role_id == 9) {

                $staff = Auth::user()->staffDetail;

                if ($staff) {
                    $query->where('organization_id', Auth::user()->organization_id)
                        ->whereHas('studDetail', function ($q) use ($staff) {
                            $q->where('department_id', $staff->department_id)
                                ->where('student_year', $staff->handle_year);
                        });
                }
                //  dd(['sql' => $query->toSql(),'bindings' => $query->getBindings(),]);
            }

            $students = $query->get();
            return view('backend.student.enroll_student', compact('courseId', 'start', 'end', 'courses', 'students'));


        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function reveuneList()
    {
        try {
            $courses = Course::with('enrolls', 'user')->withCount('enrolls')->get();
            return view('payment::admin_revenue', compact('courses'));
        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);


        }
    }

   
    public function reveuneListInstructor(Request $request)
    {
        try {
            $search_instructor = $request->get('instructor', '');
            $search_month = $request->get('month', '');
            $search_year = empty($request->year) ? date('Y') : $request->year;
            $query = CourseEnrolled::with('course', 'user', 'course.user');

            if (!empty($search_month)) {
                $from = date($search_year . '-' . $search_month . '-1');
                $to = date($search_year . '-' . $search_month . '-31');
                $query->whereBetween('created_at', [$from, $to]);
            }

            if (Auth::user()->role_id == 2) {
                $query->whereHas('course', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                });
            }
            if (!empty($request->instructor)) {
                $query->whereHas('course', function ($q) {
                    $q->where('user_id', \request('instructor'));
                });
            }

            $enrolls = $query->whereHas('course.user', function ($query) {
                $query->where('id', '!=', 1);
            })->latest()->get();


            $query2 = DB::table('subscription_courses')
                ->select('subscription_courses.*')
                ->selectRaw("SUM(revenue) as total_price");
            if (Auth::user()->role_id == 2) {
                $query2->where('user_id', '=', Auth::user()->id);
            }


            if (isModuleActive('Subscription')) {
                $subscriptionsData = $query2->groupBy('checkout_id')
                    ->latest()->get();
                $subscriptions = [];
                foreach ($subscriptionsData as $key => $data) {
                    $subscriptions[$key]['checkout_id'] = $data->checkout_id;
                    $subscriptions[$key]['date'] = $data->date;
                    $subscriptions[$key]['price'] = $data->total_price;
                    $user = User::where('id', $data->instructor_id)->first();
                    $subscriptions[$key]['instructor'] = $user->name ?? '';

                    $plan = SubscriptionCheckout::where('id', $data->checkout_id)->first();

                    $subscriptions[$key]['plan'] = $plan->plan->title ?? '';
                }


            } else {
                $subscriptions = [];
            }
            $instructors = User::where('role_id', 2)->get();
            return view('payment::instructor_revenue_report', compact('search_instructor', 'search_month', 'search_year', 'instructors', 'enrolls', 'subscriptions'));
        } catch
        (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);
        }

    }

    public function sortByDiscount(Request $request)
    {

        $rules = [
            'discount' => 'required',
            'id' => 'required'
        ];

        $this->validate($request, $rules, validationMessage($rules));

        try {
            $id = $request->id;
            $val = $request->discount;
            $start = date('Y-m-d', strtotime($request->start_date));
            $end = date('Y-m-d', strtotime($request->end_date));
            $method = $request->methods;
            if ((isset($request->end_date)) && (isset($request->start_date))) {

                if ($val == 10) {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '>', 0)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->latest()->with('user')->get();
                } else {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '=', 0)->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end)->latest()->with('user')->get();

                }
            } elseif (is_null($request->start_date) && is_null($request->end_date)) {

                if ($val == 10) {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '>', 0)->with('user', 'course')->latest()->get();
                } else {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '=', 0)->with('user', 'course')->latest()->get();

                }
            } elseif (isset($request->start_date) && is_null($request->end_date)) {


                if ($val == 10) {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '>', 0)->with('user', 'course')->whereDate('created_at', '>=', $start)->latest()->get();
                } else {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '=', 0)->with('user', 'course')->whereDate('created_at', '>=', $start)->latest()->get();

                }

            } elseif (isset($request->end_date) && is_null($start)) {

                if ($val == 10) {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '>', 0)->with('user', 'course')->whereDate('created_at', '<=', $end)->latest()->get();
                } else {

                    $logs = CourseEnrolled::where('course_id', $id)->where('discount_amount', '=', 0)->with('user', 'course')->whereDate('created_at', '<=', $end)->latest()->get();

                }
            }
            $course_id = $request->id;
            return view('payment::enroll_log', compact('logs', 'course_id'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }


    public function courseEnrolls($id)
    {

        try {
            $logs = CourseEnrolled::where('course_id', $id)->with('user', 'course')->latest()->get();
            $course_id = $id;
            return view('payment::enroll_log', compact('logs', 'course_id'));
        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);


        }
    }

    public function instructorPayout(Request $request)
    {
        $instructors = User::where('role_id', 2)->get(['id', 'name']);

        $next_pay = InstructorPayout::where('instructor_id', Auth::user()->id)->whereStatus('0')->sum('reveune');
        if (isModuleActive('Subscription')) {
            $subscriptionPay = SubscriptionCourse::where('instructor_id', Auth::user()->id)->whereStatus('0')->sum('revenue');
            $next_pay = $next_pay + $subscriptionPay;
        }


        $user = Auth::user();

        $instructorTotal = InstructorTotalPayout::where('instructor_id', $user->id)->first();
        if (!$instructorTotal) {
            $instructorTotal = new InstructorTotalPayout();
            $instructorTotal->instructor_id = $user->id;
        }
        $instructorTotal->amount = $instructorTotal->amount + $next_pay;
        $instructorTotal->save();

        $remaining = $instructorTotal->amount;

        InstructorPayout::where('instructor_id', $user->id)->whereStatus('0')->update(['status' => 1]);
        if (isModuleActive('Subscription')) {
            SubscriptionCourse::where('instructor_id', $user->id)->whereStatus('0')->update(['status' => 1]);
        }

        return view('payment::instructor_payout', compact('remaining', 'instructors'));
    }

    public function instructorRequestPayout(Request $request)
    {
        try {
            $minAmount = (int)Settings('minimum_payout_amount');
            $user = Auth::user();
            $totalPayout = InstructorTotalPayout::where('instructor_id', $user->id)->first();
            $maxAmount = $totalPayout->amount;
            $amount = $request->amount;

            if ($maxAmount < $amount) {
                Toastr::error(trans('payment.Max Limit is').' ' . getPriceFormat($maxAmount), trans('common.Error'));
                return redirect()->back();
            }
                if ($minAmount!=0 && $minAmount > $amount) {
                Toastr::error(trans('payment.Minimum Amount is').' ' . getPriceFormat($minAmount), trans('common.Error'));
                return redirect()->back();
            }

            $withdraw = new Withdraw();
            $withdraw->instructor_id = Auth::user()->id;
            $withdraw->amount = $amount;
            $withdraw->issueDate = Carbon::now();
            $withdraw->method = Auth::user()->payout;
            $withdraw->save();
            $totalPayout->amount = $totalPayout->amount - $amount;
            $totalPayout->save();


            if (Auth::user()->role_id != 1) {
                $admins = User::where('role_id', 1)->get();
                foreach ($admins as $user) {
                    $this->sendNotification('PayoutRequest',$user,[
                        'admin' => $user->name,
                        'amount' => $amount,
                        'instructor' => Auth::user()->name,
                    ]);
                }
            }

            Toastr::success(trans('lang.Payment request has been successfully submitted'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function instructorCompletePayout(Request $request)
    {
        try {
            DB::beginTransaction();
            $withdraw = Withdraw::whereId($request->withdraw_id)->whereInstructorId($request->instructor_id)->first();
            $instractor = User::find($request->instructor_id);
            $withdraw->status = 1;
            $withdraw->save();
            $instractor->balance += $withdraw->amount;
            $instractor->save();
            DB::commit();
            Toastr::success(trans('lang.Payment request has been Approved'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollback();
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function enrollDelete(Request $request)
    {

        $request->validate([
            'id' => 'required',
        ]);

        $id = $request->id;

        if (demoCheckById($id,[1,2,3,4,5,6,7,8,9,10])) {
            return redirect()->back();
        }


        if (isset($request->cancel)) {
            $deleteEnroll = $enroll = CourseCanceled::with('course', 'user')->findOrFail($id);
        } else {
            $deleteEnroll = $enroll = CourseEnrolled::with('course', 'user')->findOrFail($id);

        }

        $student = $enroll->user;

        if($student) {
            if (isset($request->refund)) {
                $student->balance = $student->balance + $enroll->purchase_price;
                $student->save();
                $act = 'Enroll_Refund';
                $status = 1;
            } else {
                $act = 'Enroll_Rejected';
                $status = 0;
            }
            $reason = $request->reason;
            if (!isset($request->cancel)) {
                $this->courseCanceled($enroll->user_id, $enroll->course_id, $enroll->purchase_price, $status, $reason);
                $deleteEnroll->delete();
            } else {
                $deleteEnroll->refund = $status;
                $deleteEnroll->save();
            }

            $this->sendNotification($act, $enroll->user, [
                'course' => $enroll->course->getTranslation('title', $enroll->user->language_code ?? config('app.fallback_locale')),
                'time' => now(),
                'reason' => $reason
            ], [
                'actionText' => trans('common.View'),
                'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$enroll->course->id, @$enroll->course->type, @$enroll->course->slug)
            ]);
            $this->sendNotification($act, $enroll->course->user, [
                'course' => $enroll->course->getTranslation('title', $enroll->course->user->language_code ?? config('app.fallback_locale')),
                'time' => now(),
                'reason' => $reason
            ], [
                'actionText' => trans('common.View'),
                'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$enroll->course->id, @$enroll->course->type, @$enroll->course->slug)
            ]);
        }

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }
    public function enrollDeleteBulk(Request $request)
    {
        $request->validate([
            'ids' => 'required',
        ]);

        $ids = explode(',', $request->ids);
        foreach ($ids as $id){

            if (demoCheckById($id,[1,2,3,4,5,6,7,8,9,10])) {
                return redirect()->back();
            }

            if (isset($request->cancel)) {
                $deleteEnroll = $enroll = CourseCanceled::with('course', 'user')->findOrFail($id);
            } else {
                $deleteEnroll = $enroll = CourseEnrolled::with('course', 'user')->findOrFail($id);

            }

            $student = $enroll->user;

            if($student && $student->email) {
                if (isset($request->refund)) {
                    $student->balance = $student->balance + $enroll->purchase_price;
                    $student->save();
                    $act = 'Enroll_Refund';
                    $status = 1;
                } else {
                    $act = 'Enroll_Rejected';
                    $status = 0;
                }
                $reason = $request->reason;
                if (!isset($request->cancel)) {
                    $this->courseCanceled($enroll->user_id, $enroll->course_id, $enroll->purchase_price, $status, $reason);
                } else {
                    $deleteEnroll->refund = $status;
                    $deleteEnroll->save();
                }

                $this->sendNotification($act, $enroll->user, [
                    'course' => $enroll->course->getTranslation('title', $enroll->user->language_code ?? config('app.fallback_locale')),
                    'time' => now(),
                    'reason' => $reason
                ], [
                    'actionText' => trans('common.View'),
                    'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$enroll->course->id, @$enroll->course->type, @$enroll->course->slug)
                ]);
                $this->sendNotification($act, $enroll->course->user, [
                    'course' => $enroll->course->getTranslation('title', $enroll->course->user->language_code ?? config('app.fallback_locale')),
                    'time' => now(),
                    'reason' => $reason
                ], [
                    'actionText' => trans('common.View'),
                    'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$enroll->course->id, @$enroll->course->type, @$enroll->course->slug)
                ]);
            }

            $deleteEnroll->delete();

        }

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required',
        ]); 
        
        // print_r($request->all()); exit;
        if (isset($request->approve)) {
             $ids = explode(',', $request->ids);
            // print_r($ids); exit;
            foreach ($ids as $id) {

                [$userId, $courseId] = explode('_', $id);

                if (demoCheckById($userId, [1,2,3,4,5,6,7,8,9,10])) {
                    return redirect()->back();
                }

                 $user = User::find($userId);
                // $course = Course::find($courseId);

                $toEmail = $user->email;
                // print_r($course); // Debugging line
                // print_r($user->)

                // if (!$approved) {
                //     print_r('Email not sent to instructor for course approval.'); // Debugging line
                // } else {
                //     print_r('Email sent to instructor for course approval.'); // Debugging line
                // }

                $enroll = CourseApproval::updateOrCreate(
                    [
                        'user_id'   => $userId,
                        'course_id' => $courseId,
                    ],
                    [
                        'remarks'    => $request->reason ?? null,
                        'status'     => $request->status ?? 1,
                        'updated_at' => now(),
                    ]
                );

                

                $this->findOrCreateCart($courseId, 1, 0, $userId, $toEmail);

                if($user && $user->email) {
                    if (isset($request->status) && $request->status == 1) {
                        $act = 'Enroll_approve';
                        $status = 1;
                    } else {
                        $act = 'Enroll_Rejected';
                        $status = 0;
                    }
                    $reason = $request->reason;

                    
                    $this->sendNotification($act, $enroll->user, [
                        'course' => $enroll->course->getTranslation('title', $enroll->user->language_code ?? config('app.fallback_locale')),
                        'time' => now(),
                        'reason' => $reason
                    ], [
                        'actionText' => trans('common.View'),
                        'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$enroll->course->id, @$enroll->course->type, @$enroll->course->slug)
                    ]);
                    $this->sendNotification($act, $enroll->course->user, [
                        'course' => $enroll->course->getTranslation('title', $enroll->course->user->language_code ?? config('app.fallback_locale')),
                        'time' => now(),
                        'reason' => $reason
                    ], [
                        'actionText' => trans('common.View'),
                        'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$enroll->course->id, @$enroll->course->type, @$enroll->course->slug)
                    ]);
                }

            }

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        }
        
    }

    private function findOrCreateCart($course, $qty, $is_store, $user, $toEmail)
    {
        // print_r($user); exit;
        $cart = Cart::where('user_id', $user)
            ->where('course_id', $course)
            ->first();

        if ($cart) {
            return false;
        }

        return $this->createCart($course, $qty, $is_store, $user, $toEmail);
    }

    private function createCart($course, $qty, $is_store, $user, $toEmail)
    {
        // print_r($course); exit;
        $userHaveCart = Cart::where('user_id', $user)->where('course_id', $course)->first();
        if ($userHaveCart) {
            $tracking =$userHaveCart->tracking;
        }else{
            $tracking = getTrx();
        }
        // print_r($tracking); exit;
        $cart = new Cart();
        $cart->user_id = $user;
        $cart->instructor_id = $user;
        $cart->course_id = $course;
        $cart->tracking = $tracking;

        $cart->price = 0;
         if (isModuleActive('EarlyBird')) {
            $early_bird_price = verifyEarlybirdOffer($course, $user);
             $cart->price = $early_bird_price['price'];
            $cart->is_earlybird_offer = $early_bird_price['price_plan_id']?1:0;
            $cart->price_plan_id = $early_bird_price['price_plan_id'];
        }

        if (isModuleActive('Store') && $is_store) {
            $this->setStoreAttributes($cart, $qty);
        }else{
            $this->applyDiscounts($cart, $user, $course);

        }

        $cart->save();

        if ($cart) {
            $courseEnrolled = CourseEnrolled::where('user_id', $user)->where('course_id', $course)->first();
            if (!$courseEnrolled) {
                $courseEnrolled = new CourseEnrolled();
                $courseEnrolled->user_id = $user ?? Auth::id();
                $courseEnrolled->tracking = $tracking ?? getTrx();
                $courseEnrolled->course_id = $course ?? 0;
                $courseEnrolled->purchase_price = 0;
                $courseEnrolled->coupon = $cart->coupon ?? null;
                $courseEnrolled->discount_amount = $cart->discount_amount ?? 0;
                $courseEnrolled->status = 1 ?? 0;
                $courseEnrolled->lms_id = 1 ?? 0;
                $courseEnrolled->save();
            }


            $course = Course::find($course);
            $user = User::find($user);

            $approved = send_course_approve_email($user, 'Enroll_Approve', [
                'email' => $user->email,
                'name' => $user->name,
                'user_name' => $user->name,
                'course' => $course->title,
                'time' => now(),
                'link' => route('continueCourse' , [$course->slug])
                ]);
           
        }

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function courseCanceled($user_id, $course_id, $price, $status, $reason)
    {
        $user = Auth::user();
        $cancle = new CourseCanceled();
        $cancle->user_id = $user_id;
        $cancle->course_id = $course_id;
        $cancle->purchase_price = (int)$price;
        $cancle->refund = $status;
        $cancle->cancel_by = $user->id;
        $cancle->reason = $reason ?? '';
        $cancle->approved_date = date('Y-m-d');

        $cancle->save();
    }

    public function getEnrollLogsData1(Request $request)
    {
        $user = Auth::user();
        $query = CourseEnrolled::select('course_enrolleds.*')->with('user:id,name,image,email', 'course:id,title', 'course.enrolls:id', 'course.certificate_records');

        
        if ($user->role_id == CORPORATE) {
            $query->whereHas('course', function ($query) use ($user) {
                $query->where('user_id', '=', $user->id);
            });

        }  else {
            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {

                $query->where(function ($query){
                    $query->whereHas('user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    })->orWhereHas('course.user', function ($q) {
                        $q->where('organization_id', Auth::id());
                        $q->orWhere('id', Auth::id());
                    });
                });
            }
        }


        if (!empty($request->course)) {
            $query->where('course_id', $request->course);
        }
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        $query->whereHas('user');
       // dd($query->toSql());
        //dd($request->all());
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($query) {
                return view('backend.student._td_bulk_checkbox', compact('query'));
            })
            ->addColumn('image', function ($query) {
                $image = $query->user->image;
                $user = $query->user->name;
                return view('backend.partials._small_profile_image', compact('image', 'user'));
            })->editColumn('user.name', function ($query) {
                return $query->user->name;

            })->editColumn('user.email', function ($query) {
                return $query->user->email;

            })
            ->editColumn('course.title', function ($query) {
                return $query->course->title;

            })
            ->editColumn('created_at', function ($query) {
                return showDate(@$query->created_at);

            })->editColumn('purchase_price', function ($query) {
                return getPriceFormat(@$query->purchase_price);

            })
            ->addColumn('action', function ($query) {

                return view('backend.student._td_enroll_log', compact('query'));

            })->rawColumns(['image', 'action'])->make(true);
    }

    public function getCancelLogsData(Request $request)
    {
        $user = Auth::user();
        if ($user->role_id == 2) {
            $query = CourseCanceled::with('user', 'course', 'confirmUser')
                ->whereHas('course', function ($query) use ($user) {
                    $query->where('user_id', '=', $user->id);
                });
        } else {
            $query = CourseCanceled::with('user', 'course', 'confirmUser');


            if (isModuleActive('Organization') && Auth::user()->isOrganization()) {
                $query->whereHas('course.user', function ($q) {
                    $q->where('organization_id', Auth::id());
                    $q->orWhere('id', Auth::id());
                });
            }
        }

        if ($request->f_course) {
            $query->where('course_id', $request->f_course);
        }
        if ($request->f_user) {
            $query->where('user_id', $request->f_user);
        }
        if ($request->f_type) {
            $query->where('refund', $request->f_type == 1 ? 1 : 0);
        }

        if ($request->f_status != null) {

            if ($request->f_status == 3) {
                $status = 0;
            } else {
                $status = $request->f_status;
            }
            $query->where('status', $status);
        }

        if ($request->f_date) {
            $query->whereBetween(DB::raw('DATE(created_at)'), formatDateRangeData($request->f_date));
        }


        $query->select('course_canceleds.*');


        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($query) {
                return $query->user->name;

            })
            ->addColumn('confirm_user', function ($query) {
                return $query->confirmUser->name;

            })
            ->addColumn('user_email', function ($query) {
                return $query->user->email;

            })
            ->addColumn('course', function ($query) {
                return $query->course->title;

            })
            ->editColumn('purchase_price', function ($query) {
                return getPriceFormat(@$query->purchase_price);

            })
            ->editColumn('created_at', function ($query) {
                return showDate(@$query->created_at);

            })
            ->editColumn('approved_date', function ($query) {
                if ($query->approved_date) {
                    return showDate(@$query->approved_date);
                }else{
                    return trans('common.N/A');
                }
                return '';

            })
            ->editColumn('refund', function ($query) {
                return $query->refund == 1 ? 'Refund' : 'Cancel';
            })
            ->addColumn('total_complete', function ($query) {

                return $query->course->userCourseCompletePercentage($query->user_id) . "%";
            })
            ->editColumn('status', function ($query) {

                if ($query->status == 1) {
                    $status = 'Approved';
                } elseif ($query->status == 0) {
                    $status = 'Pending';
                } else {
                    $status = 'Reject';
                }
                return $status;
            })
            ->addColumn('action', function ($query) {
                return view('backend.student._td_cancel_error_log', compact('query'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function getPayoutData(Request $request)
    {
        try {
            $query = Withdraw::latest()->with('user');
            if (!empty($request->month)) {
                $query->whereMonth('created_at', '=', $request->month);
            }
            if (!empty($request->year)) {
                $query->whereYear('created_at', '=', $request->year);
            }
            if (!empty($request->instructor)) {
                $query->where('instructor_id', '=', $request->instructor);
            }
            if (Auth::user()->role_id != 1) {
                $query->where('instructor_id', '=', Auth::user()->id);
            }

            return Datatables::of($query)
                ->addIndexColumn()
                ->editColumn('user.name', function ($query) {
                    return $query->user->name;
                })
                ->editColumn('amount', function ($query) {
                    return getPriceFormat($query->amount,false);
                })
                ->addColumn('requested_date', function ($query) {
                    return showDate(@$query->created_at);
                })
                ->editColumn('method', function ($query) {
                    $withdraw = $query;
                    return view('backend.partials._withdrawMethod', compact('withdraw'));
                })
                ->addColumn('status', function ($query) {
                    if ($query->status == 1) {
                        $status = trans('common.Paid');
                    } else {
                        $status = trans('common.Unpaid');
                    }
                    return $status;
                })
                ->addColumn('action', function ($query) {
                    return view('backend.instructor._td_payout_action', compact('query'));
                })
                ->rawColumns(['method', 'user.image', 'action'])
                ->make(true);

        } catch (Exception $e) {

        }
    }

    private function applyDiscounts($cart, $user, $course)
    {
        if (isModuleActive('UpcomingCourse') && $course->is_upcoming_course && $course->is_allow_prebooking) {
            $pre_booking = UpcomingCourseBooking::where('course_id', $course->id)
                ->where('user_id', $user->id)
                ->first();
            if ($pre_booking) {
                $pre_booking_amount = UpcomingCourseBookingPayment::where('booking_id', $pre_booking->id)->sum('amount');
                $cart->pre_booking_amount = $pre_booking_amount;
            }
        }

        if (isModuleActive('UserGroup') && $user->userGroup && $user->userGroup->group->status && $user->userGroup->group->discount) {
            $cart->group_discount = number_format(($cart->price * $user->userGroup->group->discount) / 100, 2);
        }

        if (hasCouponApply($course)) {
            $cart->price = getCouponPrice($course);
        }
    }


    public function getUserDate($id)
    {
        $user = User::with('image_media')->find($id);
        $user->dob = getJsDateFormat($user->dob);
        return $user;
    }

    public function removeImageByAjax(Request $request)
    {
        $table = $request->get('table');
        $name = $request->get('name');
        $id = $request->get('id');

        if ($table && $name && $id) {
            DB::table($table)->where('id', $id)->update([
                $name => ''
            ]);
        }
        return true;
    }


    public function generateCertificate(Request $request)
    {
        $enroll = CourseEnrolled::with('course', 'user')->findOrFail($request->id);
        $course = $enroll->course;

        try {
            $certificate = null;
            if (!empty($course->certificate_id)) {
                $certificate = Certificate::find($course->certificate_id);
            }
            if (!$certificate) {
                if ($course->type == 1) {
                    $certificate = Certificate::where('for_course', 1)->first();
                } elseif ($course->type == 2) {
                    $certificate = Certificate::where('for_quiz', 1)->first();
                } elseif ($course->type == 3) {
                    $certificate = Certificate::where('for_class', 1)->first();
                } else {
                    $certificate = null;
                }
            }
            if ($certificate) {
                $websiteController = new WebsiteController();
                $certificate_record = CertificateRecord::where('student_id', $enroll->user_id)->where('course_id', $enroll->course_id)->first();
                if (!$certificate_record) {
                    $certificate_record = new CertificateRecord();
                    $certificate_record->certificate_id = $websiteController->generateUniqueCode();
                    $certificate_record->student_id = $enroll->user_id;
                    $certificate_record->course_id = $enroll->course_id;
                    $certificate_record->created_by = Auth::id();
                    $certificate_record->save();
                }

                request()->certificate_id = $certificate_record->certificate_id;
                request()->course = $course;
                request()->user = $enroll->user;
                $downloadFile = new CertificateController();
                $certificate = $downloadFile->makeCertificate($certificate->id, request())['image'] ?? '';

                if ($certificate){
                    $certificate->toJpeg()->save('public/certificate/' . $certificate_record->id . '.jpg');
                }


            } else {
                Toastr::error(trans('certificate.Certificate Not Found!'), trans('common.Failed'));
                return redirect()->back();
            }
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function removeCertificate(Request $request)
    {
        $enroll = CourseEnrolled::with('course', 'user')->findOrFail($request->id);
        CertificateRecord::where('student_id', $enroll->user_id)->where('course_id', $enroll->course_id)->delete();
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function institutionWiseUser(Request $request)
    {
        if ($request->ajax()){
            $user = Auth::user();
            $query = User::where('status', 1);
            if (isModuleActive('LmsSaas')) {
                $query->where('lms_id', app('institute')->id);
            } else {
                $query->where('lms_id', 1);
            }
            if (isModuleActive('UserType')) {
                $query->whereHas('userRoles', function ($q) {
                    $q->where('role_id', 3);
                });
            } else {
                $query->where('role_id', 3);
            }
            if (isModuleActive('Organization') && $user->isOrganization()) {
                $query->where('organization_id', $user->id);
            }
            if ($request->institute) {
                $query->where('institute_id', $request->institute);
            }

            $query->with('studentInstitute');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image', function ($query) {
                    return view('backend.partials._td_image', compact('query'));
                })->editColumn('name', function ($query) {
                    $title = $query->name;
                    $link = $query->username ? route('profileUniqueUrl', $query->username) : '';
                    return view('studentsetting::partials._td_link', compact('query', 'link', 'title'));

                })->editColumn('email', function ($query) {
                    return $query->email;

                })
                ->editColumn('phone', function ($query) {
                    return translatedNumber($query->phone);

                })
                ->addColumn('institute_name', function ($query) {
                    return $query->studentInstitute?->name;

                })
                 ->rawColumns(['image','name'])
                ->make(true);
        }

        $institutes =Institute::where('status', 1)->select('id', 'name')->get();

        return view('backend.report.institution_wise_user',compact('institutes'));
    }
    public function institutionWisePerformance(Request $request)
    {
        if ($request->ajax()) {
           return $this->statisticDatatable($request);
        }
        $institutes =Institute::where('status', 1)->select('id', 'name')->get();
        return view('backend.report.institution_wise_performance',compact('institutes'));

    }

    public function statisticDatatable($request)
    {
        $filters['status'] =1;
        if ($request->institute) {
            $filters['institute_id'] = $request->institute;
        }
        if ($request->user) {
            $filters['user_id'] = $request->user;
        }

        $query = $this->courseStatisticFilterQuery();
        if ($request->type==1) {
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('required_type', function ($query) {
                    return $query->required_type == 1 ? trans('courses.Compulsory') : trans('courses.Open');
                })->editColumn('mode_of_delivery', function ($query) {
                    if ($query->mode_of_delivery == 1) {
                        $title = trans('courses.Online');

                    } elseif ($query->mode_of_delivery == 2) {
                        $title = trans('courses.Distance Learning');
                    } else {
                        if (isModuleActive('Org')) {
                            $title = trans('courses.Offline');
                        } else {
                            $title = trans('courses.Face-to-Face');
                        }
                    }
                    return $title;
                })
                ->addColumn('type', function ($query) {
                    return $query->type == 1 ? trans('courses.Course') : trans('quiz.Quiz');

                })
                ->editColumn('total_enrolled', function ($query) use ($filters) {
                    return translatedNumber($query->totalStatistic($filters)['total_enroll']);
                })->editColumn('title', function ($query) {
                    return $query->title;
                })
                ->addColumn('not_start', function ($query) use ($filters) {
                    return translatedNumber($query->totalStatistic($filters)['not_start']);
                })
                ->addColumn('in_process', function ($query) use ($filters){
                    return translatedNumber($query->totalStatistic($filters)['in_process']);
                })
                ->addColumn('finished', function ($query) use ($filters) {
                    return translatedNumber($query->totalStatistic($filters)['finished']);
                })
                ->addColumn('finished_rate', function ($query) use ($filters) {
                    $finished = $query->totalStatistic($filters)['finished'];
                    $not_start = $query->totalStatistic($filters)['not_start'];
                    $in_process = $query->totalStatistic($filters)['in_process'];
                    $total = $finished+$not_start+$in_process;



                    $percentage = 0;
                    if ($total != 0) {
                        $percentage = ($finished / $total) * 100;
                        if ($percentage > 100) {
                            $percentage = 100;
                        }
                    }
                    return translatedNumber(round($percentage)) . '%';
                })
                ->make(true);
        }elseif($request->type==2){
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('title', function ($query) {
                    return $query->title;
                })
                ->editColumn('required_type', function ($query) {
                    return $query->required_type == 1 ? trans('courses.Compulsory') : trans('courses.Open');
                })->editColumn('mode_of_delivery', function ($query) {
                    if ($query->mode_of_delivery == 1) {
                        $title = trans('courses.Online');

                    } elseif ($query->mode_of_delivery == 2) {
                        $title = trans('courses.Distance Learning');
                    } else {
                        if (isModuleActive('Org')) {
                            $title = trans('courses.Offline');
                        } else {
                            $title = trans('courses.Face-to-Face');
                        }
                    }
                    return $title;
                })
                ->addColumn('type', function ($query) {
                    return $query->type == 1 ? trans('courses.Course') : trans('quiz.Quiz');

                })
                ->editColumn('total_enrolled', function ($query) use ($filters) {
                    return translatedNumber($query->totalQuizStatistic($filters)['total_enroll']);
                })
                ->addColumn('not_start', function ($query) use ($filters) {
                    return translatedNumber($query->totalQuizStatistic($filters)['not_start']);
                })
                ->addColumn('fail', function ($query) use ($filters){
                    return translatedNumber($query->totalQuizStatistic($filters)['fail']);
                })
                ->addColumn('pass', function ($query) use ($filters) {
                    return translatedNumber($query->totalQuizStatistic($filters)['pass']);
                })
                ->addColumn('pass_rate', function ($query) use ($filters) {
                    $pass = $query->totalQuizStatistic($filters)['pass'];
                    $total = $query->total_enrolled;
                    $percentage = 0;
                    if ($total != 0) {
                        $percentage = ($pass / $total) * 100;
                        if ($percentage > 100) {
                            $percentage = 100;
                        }
                    }
                    return translatedNumber($percentage) . '%';
                })
                ->make(true);
        }elseif($request->type==3){
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('required_type', function ($query) {
                    return $query->required_type == 1 ? trans('courses.Compulsory') : trans('courses.Open');
                })->editColumn('mode_of_delivery', function ($query) {
                    if ($query->mode_of_delivery == 1) {
                        $title = trans('courses.Online');

                    } elseif ($query->mode_of_delivery == 2) {
                        $title = trans('courses.Distance Learning');
                    } else {
                        if (isModuleActive('Org')) {
                            $title = trans('courses.Offline');
                        } else {
                            $title = trans('courses.Face-to-Face');
                        }
                    }
                    return $title;
                })
                ->addColumn('type', function ($query) {
                    return  trans('virtual-class.Virtual Class');

                })
                ->editColumn('total_enrolled', function ($query) use ($filters) {
                    return translatedNumber($query->totalClassStatistic($filters)['total_enroll']);
                })->editColumn('title', function ($query) use ($filters){
                    return $query->title;
                })
                ->addColumn('not_start', function ($query) use ($filters) {
                    return translatedNumber($query->totalClassStatistic($filters)['not_start']);
                })
                ->addColumn('in_process', function ($query) use ($filters) {
                    return translatedNumber($query->totalClassStatistic($filters)['in_process']);
                })
                ->addColumn('finished', function ($query) use ($filters) {
                    return translatedNumber($query->totalClassStatistic($filters)['finished']);
                })
                ->addColumn('finished_rate', function ($query) use ($filters) {
                    $finished = $query->totalClassStatistic($filters)['finished'];
                    $total = $query->total_enrolled;
                    $percentage = 0;
                    if ($total != 0) {
                        $percentage = ($finished / $total) * 100;
                        if ($percentage > 100) {
                            $percentage = 100;
                        }
                    }
                    return translatedNumber(round($percentage)) . '%';
                })
                ->make(true);
        }else{
            return  false;
        }
    }

    public function courseStatisticFilterQuery()
    {
        $query = Course::with('category', 'user', 'enrolls')->whereIn('type', [1,2,3]);
        if (\request('type')) {
            $query->where('type', \request('type'));
        }


        $query->whereHas('enrolls', function ($q) {
            $q->whereHas('user', function ($q2) {

                $user= \request()->get('user');
                $institute = \request()->get('institute');
                if ($user) {
                    $q2->where('id', $user);
                } elseif ($institute) {
                    $q2->where('institute_id', $institute);
                }
                 $q2->where('status', 1);
            });
        });

        if (isInstructor()) {
            $query->where('user_id', '=', Auth::id());
            $query->orWhere('assistant_instructors', 'like', '%"{' . Auth::id() . '}"%');
        }
        return $query;
    }

    public function userWisePerformance(Request $request)
    {
        if ($request->ajax()) {
            return $this->statisticDatatable($request);
        }
        $users =User::where('status', 1)->where('role_id',3)->select('id', 'name')->get();
        return view('backend.report.user_wise_performance',compact('users'));

    }

    
    public function customList(Request $request)
    {
        try {

            $course_id   = $request->get('course_id');
            $group_id    = $request->get('group_id');
            $module_wise = $request->get('module_wise');
            $lesson_wise = $request->get('lesson_wise');

            $query = CourseEnrolled::with([
                'course',
                'user',
                'course.user'
            ]);

            // Filter courses by organization through course owner
            $query->whereHas('course.user', function ($q) {
                $q->where('organization_id', Auth::user()->organization_id);
            });

            // Course Filter
            if (!empty($course_id)) {
                $query->where('course_id', $course_id);
            }

            // Group Filter
            if (!empty($group_id) && strpos($group_id, '_') !== false) {

                [$type, $id] = explode('_', $group_id, 2);

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
                    $query->whereHas('user', function ($q) use ($columnMap, $type, $id) {
                        $q->where($columnMap[$type], $id);
                    });
                }
            }

            $enrolls = $query->latest()->get();

            $instructors = User::where('role_id', 2)->get();

            // Verify organization_id exists in courses table
            $courses = Course::where('organization_id', Auth::user()->organization_id)
                ->where('type', 1)
                ->get();
            // dd(\Schema::getColumnListing('courses'));
            $usersDatas = $this->usersData();

            return view('payment::custom_courses', compact(
                'enrolls',
                'courses',
                'instructors',
                'usersDatas'
            ));

        } catch (\Exception $e) {

            return response()->json([
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);
        }
    }

    public function courseList(Request $request)
    {
        try {
            // print_r($request->all()); exit;
            $course_id = $request->get('course_id');
            

            $query = CourseEnrolled::with('course', 'user', 'course.user');

            $query->whereHas('course', function ($q) {
                $q->where('organization_id', Auth::user()->organization_id);
            });

            // ✅ Filter by Course
            if (!empty($course_id)) {
                $query->where('course_id', $course_id);
            }

            // ✅ Filter by Group
           

            // ✅ Execute query
            $enrolls = $query->latest()->get();

            // ✅ Required data for view

            // print_r($enrolls); exit;

            // print_r($enrolls); exit; 
            $instructors = User::where('role_id', 2)->get();

            $courses = Course::where('organization_id', Auth::user()->organization_id)
                ->where('type', 1)
                ->get();

            // ✅ FIX: convert JSON → array

            return view('payment::course_lists', compact(
                'enrolls',
                'courses',
                'instructors'
            ));

        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);
        }
    }

    function usersData()
    {
        $orgId = auth()->user()->organization_id;

        // -------------------------------
        // ✅ GROUP DATA WITH JOINS
        // -------------------------------

        $profiles = User::join('profile', 'profile.id', '=', 'users.profile_id')
            ->selectRaw('profile.id, profile.profile_name as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('profile.id', 'profile.profile_name')
            ->get();

        $bus = User::join('business_unit', 'business_unit.id', '=', 'users.bu')
            ->selectRaw('business_unit.id, business_unit.bu_name as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('business_unit.id', 'business_unit.bu_name')
            ->get();

        $departments = User::join('department', 'department.id', '=', 'users.dept_id')
            ->selectRaw('department.id, department.dept_name as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('department.id', 'department.dept_name')
            ->get();

        $countries = User::join('associate_country', 'associate_country.id', '=', 'users.country')
            ->selectRaw('associate_country.id, associate_country.country_name as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('associate_country.id', 'associate_country.country_name')
            ->get();

        $states = User::join('associate_state', 'associate_state.id', '=', 'users.state')
            ->selectRaw('associate_state.id, associate_state.state_name as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('associate_state.id', 'associate_state.state_name')
            ->get();

        $cities = User::join('associate_city', 'associate_city.id', '=', 'users.city')
            ->selectRaw('associate_city.id, associate_city.city_name as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('associate_city.id', 'associate_city.city_name')
            ->get();

        $locations = User::join('location_code', 'location_code.id', '=', 'users.location_code')
            ->selectRaw('location_code.id, location_code.location_code as name, COUNT(users.id) as total')
            ->where('users.organization_id', $orgId)
            ->groupBy('location_code.id', 'location_code.location_code')
            ->get();

        // -------------------------------
        // ✅ HELPER FUNCTION
        // -------------------------------
        function buildGroup($data, $type)
        {
            $arr = [];

            foreach ($data as $item) {
                $arr[] = [
                    'id' => $type . '_' . $item->id, // ✅ IMPORTANT
                    'text' => $item->name . ' (' . $item->total . ')'
                ];
            }

            return $arr;
        }

        // -------------------------------
        // ✅ FINAL RESULT
        // -------------------------------
        $results = [];

        // PROFILE
        if ($profiles->count()) {
            $results[] = ['text' => 'PROFILE', 'children' => buildGroup($profiles, 'profile')];
        }

        // BU
        if ($bus->count()) {
            $results[] = ['text' => 'BUSINESS UNIT', 'children' => buildGroup($bus, 'bu')];
        }

        // DEPARTMENT
        if ($departments->count()) {
            $results[] = ['text' => 'DEPARTMENT', 'children' => buildGroup($departments, 'dept')];
        }

        // COUNTRY
        if ($countries->count()) {
            $results[] = ['text' => 'COUNTRY', 'children' => buildGroup($countries, 'country')];
        }

        // STATE
        if ($states->count()) {
            $results[] = ['text' => 'STATE', 'children' => buildGroup($states, 'state')];
        }

        // CITY
        if ($cities->count()) {
            $results[] = ['text' => 'CITY', 'children' => buildGroup($cities, 'city')];
        }

        // LOCATION
        if ($locations->count()) {
            $results[] = ['text' => 'LOCATION', 'children' => buildGroup($locations, 'location')];
        }

        // -------------------------------
        // ✅ MANAGEMENT (Managers)
        // -------------------------------
        $teamCounts = User::selectRaw('manager_email, COUNT(*) as total')
            ->where('organization_id', $orgId)
            ->whereNotNull('manager_email')
            ->groupBy('manager_email')
            ->pluck('total', 'manager_email')
            ->toArray();
        $managers = User::where('role_id', 13)
            ->where('organization_id', $orgId)
            ->get();

        if ($managers->count()) {

            $managerArr = [];

            foreach ($managers as $manager) {

                $count = $teamCounts[$manager->email] ?? 0;

                $managerArr[] = [
                    'id' => 'manager_' . $manager->id,
                    'text' => $manager->name . ' (' . $count . ')'
                ];
            }

            $results[] = [
                'text' => 'MANAGEMENT',
                'children' => $managerArr
            ];
        }

        return [
            'results' => $results
        ];
    }

    
    public function feedbackReport(Request $request)
    {
        try {
            // print_r($request->all()); exit;
            $course_id = $request->get('course_id');
            $group_id  = $request->get('group_id');

            $query = CourseEnrolled::with('course', 'user', 'course.user');

            $query->whereHas('course', function ($q) {
                $q->where('organization_id', Auth::user()->organization_id);
            });

            // ✅ Filter by Course
            if (!empty($course_id)) {
                $query->where('course_id', $course_id);
            }

            // ✅ Filter by Group
            if (!empty($group_id)) {

                $parts = explode('_', $group_id);

                if (count($parts) == 2) {
                    [$type, $id] = $parts;

                    $query->whereHas('user', function ($q) use ($type, $id) {

                        switch ($type) {

                            case 'profile':
                                $q->where('profile_id', $id);
                                break;

                            case 'bu':
                                $q->where('bu', $id);
                                break;

                            case 'dept':
                                $q->where('dept_id', $id);
                                break;

                            case 'country':
                                $q->where('country', $id);
                                break;

                            case 'state':
                                $q->where('state', $id);
                                break;

                            case 'city':
                                $q->where('city', $id);
                                break;

                            case 'location':
                                $q->where('location_code', $id);
                                break;

                            case 'manager':
                                $q->where('mgt_id', $id); // ✅ correct column
                                break;
                        }
                    });
                }
            }

            // ✅ Execute query
            $enrolls = $query->latest()->get();

            // ✅ Required data for view

            // print_r($enrolls); exit;

            // print_r($enrolls); exit; 
            $instructors = User::where('role_id', 2)->get();

            $courses = Course::where('organization_id', Auth::user()->organization_id)
                ->where('type', 1)
                ->get();

            // ✅ FIX: convert JSON → array
            $usersDatas = $this->usersData();

            return view('payment::feedback_lists', compact(
                'enrolls',
                'courses',
                'instructors',
                'usersDatas'
            ));

        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);
        }
    }
    
    public function learningPlanReport(Request $request)
    {
        try {
            // print_r($request->all()); exit;
            $course_id = $request->get('course_id');
            $group_id  = $request->get('group_id');

            $query = CourseEnrolled::with('course', 'user', 'course.user');

            $query->whereHas('course', function ($q) {
                $q->where('organization_id', Auth::user()->organization_id);
            });

            // ✅ Filter by Course
            if (!empty($course_id)) {
                $query->where('course_id', $course_id);
            }

            // ✅ Filter by Group
            if (!empty($group_id)) {

                $parts = explode('_', $group_id);

                if (count($parts) == 2) {
                    [$type, $id] = $parts;

                    $query->whereHas('user', function ($q) use ($type, $id) {

                        switch ($type) {

                            case 'profile':
                                $q->where('profile_id', $id);
                                break;

                            case 'bu':
                                $q->where('bu', $id);
                                break;

                            case 'dept':
                                $q->where('dept_id', $id);
                                break;

                            case 'country':
                                $q->where('country', $id);
                                break;

                            case 'state':
                                $q->where('state', $id);
                                break;

                            case 'city':
                                $q->where('city', $id);
                                break;

                            case 'location':
                                $q->where('location_code', $id);
                                break;

                            case 'manager':
                                $q->where('mgt_id', $id); // ✅ correct column
                                break;
                        }
                    });
                }
            }

            // ✅ Execute query
            $enrolls = $query->latest()->get();

            // ✅ Required data for view

            // print_r($enrolls); exit;

            // print_r($enrolls); exit; 
            $instructors = User::where('role_id', 2)->get();

            $courses = Course::where('organization_id', Auth::user()->organization_id)
                ->where('type', 1)
                ->get();

            // ✅ FIX: convert JSON → array
            $usersDatas = $this->usersData();

            return view('payment::learning_plan_report', compact(
                'enrolls',
                'courses',
                'instructors',
                'usersDatas'
            ));

        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);
        }
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new LMSExport($request), 'custom_courses.xlsx');
    }

    public function customDashboard(Request $request)
    {
        try {
            // print_r($request->all()); exit;
            $course_id = $request->get('course_id');
            $group_id  = $request->get('group_id');

            $query = CourseEnrolled::with('course', 'user', 'course.user');

            $query->whereHas('course', function ($q) {
                $q->where('organization_id', Auth::user()->organization_id);
            });

            // ✅ Filter by Course
            if (!empty($course_id)) {
                $query->where('course_id', $course_id);
            }

            // ✅ Filter by Group
            if (!empty($group_id)) {

                $parts = explode('_', $group_id);

                if (count($parts) == 2) {
                    [$type, $id] = $parts;

                    $query->whereHas('user', function ($q) use ($type, $id) {

                        switch ($type) {

                            case 'profile':
                                $q->where('profile_id', $id);
                                break;

                            case 'bu':
                                $q->where('bu', $id);
                                break;

                            case 'dept':
                                $q->where('dept_id', $id);
                                break;

                            case 'country':
                                $q->where('country', $id);
                                break;

                            case 'state':
                                $q->where('state', $id);
                                break;

                            case 'city':
                                $q->where('city', $id);
                                break;

                            case 'location':
                                $q->where('location_code', $id);
                                break;

                            case 'manager':
                                $q->where('mgt_id', $id); // ✅ correct column
                                break;
                        }
                    });
                }
            }

            // ✅ Execute query
            $enrolls = $query->latest()->get();

            // ✅ Required data for view

            // print_r($enrolls); exit;

            // print_r($enrolls); exit; 
            $instructors = User::where('role_id', 2)->get();

            $courses = Course::where('organization_id', Auth::user()->organization_id)
                ->where('type', 1)
                ->get();

            // ✅ FIX: convert JSON → array
            $usersDatas = $this->usersData();

            return view('payment::custom_dashboard', compact(
                'enrolls',
                'courses',
                'instructors',
                'usersDatas'
            ));

        } catch (Exception $e) {
            return response()->json(['error' => trans("lang.Oops, Something Went Wrong")]);
        }
    }

    public function SamplecustomDashboard(Request $request)
    {
        return view('payment::sample_custom_dashboard');
    }

    public function getCustomDashboardData(Request $request)
    {
        // This is a placeholder function. You can implement the logic to fetch and return data based on the filters applied in the custom dashboard.
        // For now, it returns a static response.

        $groupType = $request->group_type;
        $groupValues = $request->group_values;

        $query = DB::table('course_enrolleds as ce')
                    ->join('users as u', 'u.id', '=', 'ce.user_id')
                    ->join('courses as c', 'c.id', '=', 'ce.course_id');

        /*
        |--------------------------------------------------------------------------
        | Dynamic Filter
        |--------------------------------------------------------------------------
        */

        if ($groupType == 1) {

            // Profile
            $query->whereIn('u.profile_id', $groupValues);

        } elseif ($groupType == 2) {

            // Business Unit
            $query->whereIn('u.business_unit_id', $groupValues);

        } elseif ($groupType == 3) {

            // Department
            $query->whereIn('u.department_id', $groupValues);
        }

        $data = $query->select(
                    'c.title as course',
                    DB::raw('DATE(ce.created_at) as date'),
                    DB::raw('COUNT(DISTINCT ce.user_id) as reach'),
                    
                    DB::raw('COUNT(DISTINCT ce.course_id) as total_courses')
                )
                ->groupBy('c.id')
                ->get();

        return response()->json($data);
    }

    public function getGroupData(Request $request)
    {
        $type = $request->type;

        // return $type;

        switch ($type) {
            case 1:
                $data = Profile::select('id', 'profile_name as name')->get();
                break;

            case 2:
                $data = BusinessUnit::select('id', 'bu_name as name')->get();
                break;

            case 3:
                $data = Department::select('id', 'dept_name as name')->get();
                break;

            case 4:
                $data = AssociateCountry::select('id', 'country_name as name')->get();
                break;

            case 5:
                $data = AssociateState::select('id', 'state_name as name')->get();
                break;

            case 6:
                $data = AssociateCity::select('id', 'city_name as name')->get();
                break;

            case 7:
                $data = LocationCode::select('id', 'location_code as name')->get();
                break;

            case 8:
                $data = Management::select('id', 'management_name as name')->get();
                break;

            default:
                $data = [];
        }

        return response()->json($data);
    }
    

    public function reportData(Request $request)
    {
        // SAMPLE DATA (replace with DB later)
        $data = [
            ['group'=>'Automation','time'=>10,'reach'=>5,'courses'=>2],
            ['group'=>'Website','time'=>20,'reach'=>25,'courses'=>8],
            ['group'=>'Education','time'=>15,'reach'=>10,'courses'=>6],
        ];

        // FILTER (example)
        if ($request->group_value) {
            $data = collect($data)->filter(function($item) use ($request){
                return true; // apply real filter
            })->values();
        }

        return response()->json($data);
    }

    public function waitingList()
    {
        return view('backend.waiting_list');
    }

    public function getGroupValues(Request $request)
    {
        $type = $request->type;

        $org_id = Auth::user()->organization_id;

        if ($type == 1) {

            // Profiles table
            $data = DB::table('profile')
                        ->select('id', 'profile_name as name')
                        ->where('organization_id', $org_id)
                        ->get();

        } elseif ($type == 2) {

            // Business Unit table
            $data = DB::table('business_unit')
                        ->select('id', 'bu_name as name')
                        ->where('organization_id', $org_id)
                        ->get();

        } elseif ($type == 3) {

            // Department table
            $data = DB::table('department')
                        ->select('id', 'dept_name as name')
                        ->where('organization_id', $org_id)
                        ->get();

        } else {

            $data = [];
        }

        return response()->json($data);
    }

    public function getFeedbackReport(Request $request)
    {
        $groupType = $request->group_type;
        $groupValues = $request->group_values;

        $query = DB::table('course_enrolleds as ce')
                    ->join('users as u', 'u.id', '=', 'ce.user_id')
                    ->join('courses as c', 'c.id', '=', 'ce.course_id');

        /*
        |--------------------------------------------------------------------------
        | Dynamic Filter
        |--------------------------------------------------------------------------
        */

        if ($groupType == 1) {

            // Profile
            $query->whereIn('u.profile_id', $groupValues);

        } elseif ($groupType == 2) {

            // Business Unit
            $query->whereIn('u.business_unit_id', $groupValues);

        } elseif ($groupType == 3) {

            // Department
            $query->whereIn('u.department_id', $groupValues);
        }

        $data = $query->select(
                    'c.title as course',
                    DB::raw('DATE(ce.created_at) as date'),
                    DB::raw('COUNT(DISTINCT ce.user_id) as reach'),
                    
                    DB::raw('COUNT(DISTINCT ce.course_id) as total_courses')
                )
                ->groupBy('c.id')
                ->get();

        return response()->json($data);
    }

    
    
}
