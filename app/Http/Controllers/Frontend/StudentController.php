<?php

namespace App\Http\Controllers\Frontend;


use App\Events\OneToOneConnection;
use App\Http\Controllers\Controller;
use App\Jobs\SendGeneralEmail;
use App\StudentCustomField;
use App\TopicReport;
use App\Traits\GoogleAnalytics4;
use App\Traits\ImageStore;
use App\Traits\SendNotification;
use App\User;
use App\UserLogin;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Modules\Affiliate\Events\ReferralPayment;
use Modules\Assignment\Entities\InfixAssignAssignment;
use Modules\Assignment\Entities\InfixAssignment;
use Modules\Certificate\Entities\Certificate;
use Modules\Certificate\Entities\CertificateRecord;
use Modules\Certificate\Http\Controllers\CertificateController;
use Modules\CertificatePro\Entities\CertificateTemplate;
use Modules\Coupons\Entities\Coupon;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseBadge;
use Modules\CourseSetting\Entities\CourseCanceled;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\CourseReveiw;
use Modules\CourseSetting\Http\Controllers\CourseSettingController;
use Modules\OfflinePayment\Entities\OfflinePayment;
use Modules\Payment\Entities\Cart;
use Modules\Payment\Entities\Checkout;
use Modules\Quiz\Entities\QuizTest;
use Modules\Setting\Entities\UserGamificationPoint;
use Modules\Store\Entities\ImageRefundRequest;
use Modules\Store\Entities\OrderPackageDetail;
use Modules\Store\Entities\Product;
use Modules\Store\Entities\RefundProduct;
use Modules\Store\Entities\RefundRequest;
use Modules\Store\Entities\RefundRequestDetail;
use Modules\Store\Entities\ShippingConfiguration;
use Modules\Store\Entities\ShippingMethod;
use Modules\Store\Entities\StoreBankPayment;
use Modules\Subscription\Entities\SubscriptionCart;
use Modules\Subscription\Entities\SubscriptionCheckout;
use Modules\Survey\Entities\Survey;
use Modules\Survey\Http\Controllers\SurveyController;
use Modules\VirtualClass\Entities\ClassComplete;
use App\Models\LearningPathCourse;
use App\Models\Announcements;
use App\Models\announcementUser;
use Modules\CourseSetting\Entities\Category;
use Throwable;
use function redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Models\Tenants;
use App\Models\CorporateSkill;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Profile;
use App\Models\TransferResourceDetail;
use App\Mail\TransferRequestList;
use Illuminate\Support\Facades\Mail;
use App\Models\UserTransferAct;
use App\Models\skills;
use App\Models\Department;
use App\Models\Management;
use App\Models\AssociateState;
use App\Models\AssociateCity;
use App\Models\LocationCode;
use App\Models\AssociateCountry;
use App\Models\UserSkills;
use App\Models\BusinessUnit;
use App\Models\Feedbacks;
use App\Models\FeedbackAllocation;
use App\Models\UserLearningActivity;
use Modules\CourseSetting\Entities\Lesson;

class StudentController extends Controller
{
    use ImageStore, GoogleAnalytics4, SendNotification;

    public function __construct()
    {
        $this->middleware(['maintenanceMode', 'onlyAppMode']);
    }

    public function myDashboard()
    {
        try {
            if (isModuleActive('Membership') && auth()->user()->student_type == 'membership' ){
                return redirect()->route('membership.myMembership');
            }
            return view(theme('pages.myDashboard'));
        } catch (Exception $e) {
            
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myCourses(Request $request)
    {
        try {
            
            return view(theme('pages.myCourses'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myQuizzes(Request $request)
    {
        try {
            
            return view(theme('pages.myQuizzes'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    

    public function myFeedbacks(Request $request)
    {
        try {
            // print_r($request->all()); exit;
            return view(theme('pages.myFeedbacks'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myPrivateCourses(Request $request)
    {
        try {
            
            return view(theme('pages.myCourses'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myOrganizationCatalog(Request $request)
    {
        try {
              return view(theme('pages.myOrganizationCatalog'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function learningPlan(Request $request)
    {
        try {
            return view(theme('pages.learningPlan'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function learningPath(Request $request)
    {
        try {
            return view(theme('pages.learningPath'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function privateCourses(Request $request)
    {
        try {
            return view(theme('pages.private_courses'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function learningPathPrivateCourses(Request $request)
    {
        try {
            return view(theme('pages.learning_path_private_courses'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myAppointment(Request $request)
    {
        try {
            return view(theme('pages.appointment_myAppointment'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myWishlists()
    {
        try {
            return view(theme('pages.myWishlists'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myPurchases()
    {
        try {
            return view(theme('pages.myPurchases'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myBundle()
    {
        try {
            return view(theme('pages.myBundle'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function topicReport($id)
    {

        try {
            $check = TopicReport::where('report_by', Auth::user()->id)->where('report_for', $id)->first();
            if ($check == null) {
                $report = new TopicReport();
                $report->report_by = Auth::user()->id;
                $report->report_for = $id;
                $report->save();
                Toastr::success(trans('student.Report is under review'), trans('common.Success'));
                return redirect()->back();
            } else {

                Toastr::error(trans('student.You have already done report'), trans('common.Failed'));
                return redirect()->back();
            }

        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myCertificate(Request $request)

    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        try {
            $order = $request->order;
            $query = $request->query;
            return view(theme('pages.myCertificate'), compact('order', 'query'));

        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myAssignment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        try {
            return view(theme('pages.myAssignment'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myProfile()
    {
        try {
            $custom_field = StudentCustomField::getData();
            return view(theme('pages.myProfile'), compact('custom_field'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function myAssignmentDetails($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        try {

            $assign_assignment = InfixAssignAssignment::where('student_id', Auth::user()->id)->where('id', $id)->first();
            if ($assign_assignment == null) {
                Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
                return redirect()->back();
            }
            $assignment_info = InfixAssignment::where('id', $assign_assignment->assignment_id)->first();

            return view(theme('pages.assignment_details'), compact('assignment_info', 'assign_assignment'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function ajaxUploadProfilePic(Request $request)
    {
        try {
            $fileName = "";
            $user = Auth::user();
            if ($request->file('file') != "") {
                $user->image = $fileName = $this->saveImage($request->file('file'));
            }
            $user->save();
            if ($request->ajax()) {
                return $fileName;
            } else {
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect()->back();
            }

        } catch (Throwable $th) {
            return $th;
        }

    }

    public function myProfileUpdate(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $custom_field = StudentCustomField::getData();

        if (Auth::user()->role_id == 1) {
            $validate_rules = [
                'name' => 'required',
                'email' => 'required|email',

            ];
        } else {
            $validate_rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . Auth::id(),
                'username' => 'required|unique:users,username,' . Auth::id(),
                'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:1|unique:users,phone,' . Auth::id(),
                'country' => 'required',
                'company_id' => $custom_field->required_company ? 'required' : 'nullable',
                'student_type' => $custom_field->required_student_type ? 'required' : 'nullable',
                'identification_number' => $custom_field->required_identification_number ? 'required' : 'nullable',
                'job_title' => $custom_field->required_job_title ? 'required' : 'nullable',
                'gender' => $custom_field->required_gender ? 'required' : 'nullable',
                'dob' => $custom_field->required_dob ? 'required' : 'nullable',
            ];
        }

        $request->validate($validate_rules, validationMessage($validate_rules));


        try {

            if (demoCheck()) {
                return redirect()->back();
            }

            $lang = explode('|', $request->language ?? '');

            $user = Auth::user();
            if (empty($request->phone)) {
                $phone = null;
            } else {
                $phone = $request->phone;
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $phone;
            $user->address = $request->address;
            $user->language_id = (int)$lang[0] ?? 19;
            $user->language_code = $lang[1] ?? 'en';
            $user->language_name = $lang[2] ?? 'English';
            $user->language_rtl = (int)$lang[3] ?? 0;
            $user->city = (int)$request->city;
            $user->country = (int)$request->country;
            $user->state = (int)$request->state;
            $user->zip = $request->zip;

            $user->student_type = $request->student_type;
            $user->identification_number = $request->identification_number;
            $user->job_title = $request->job_title;
            $user->dob = $request->dob;
            $user->gender = $request->gender;

            $user->currency_id = (int)Settings('currency_id');
            $user->facebook = $request->facebook;
            $user->twitter = $request->twitter;
            $user->linkedin = $request->linkedin;
            $user->instagram = $request->instagram;
            $user->youtube = $request->youtube;
            $user->headline = $request->headline;
            $user->about = clean($request->about);
            if ($request->file('image') != "") {
                $user->image = $this->saveImage($request->file('image'));
            }

            if ($user->role_id==3){
                $tfaStatus =(int)Settings('enable_student_two_fa');
            }else{
                $tfaStatus =(int)Settings('enable_two_fa');
            }
            if (isModuleActive('TwoFA') && $tfaStatus) {
                $user->two_step_verification = $request->two_step_verification;
                $user->two_fa_expired_time = $request->two_step_verification == 1 ? $request->two_fa_expired_time : null;
                $user->google2fa_secret = $request->two_step_verification == 2 ? app('pragmarx.google2fa')->generateSecretKey() : null;
            }

            $user->save();

            if ($request->company_name) {
                $user->company->update([
                    'name' => $request->company_name,
                    'sector' => $request->company_sector,
                    'phone' => $request->company_phone,
                    'address' => $request->company_address,
                ]);
            }


            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }


    public function myAccount()
    {
        try {
            return view(theme('pages.myAccount'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function MyUpdatePassword(Request $request)
    {
        print_r($request->all()); exit;
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required_with:new_password|same:new_password|min:8'
        ];

        $this->validate($request, $rules, validationMessage($rules));

        try {
            if (demoCheck()) {
                return redirect()->back();
            }

            $user = Auth::user();

            print_r($request->all()); exit;
            if (!Hash::check($request->old_password, $user->password)) {
                Toastr::error(trans('student.Password Do not match'), trans('common.Failed'));
                return redirect()->back();
            }

            $user->update([
                'password' => bcrypt($request->new_password)
            ]);

            $login = UserLogin::where('user_id', Auth::id())->where('status', 1)->latest()->first();
            
            if ($login) {
                $login->status = 0;
                $login->logout_at = Carbon::now(Settings('active_time_zone'));
                $login->save();
            }


            SendGeneralEmail::dispatch($user, 'PASS_UPDATE', [
                'time' => Carbon::now()->translatedFormat('d-M-Y, g:i A')
            ]);

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
//            Auth::logout();

            return back();


        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }


    public function MyAccountDelete(Request $request)
    {
        $rules = [
            'old_password' => 'required',
        ];

        $this->validate($request, $rules, validationMessage($rules));

        try {
            if (demoCheck()) {
                return redirect()->back();
            }

            $user = Auth::user();


            if (!Hash::check($request->old_password, $user->password)) {
                Toastr::error(__('student.Password does not match'), __('common.Failed'));
                return redirect()->back();
            }

            $user->delete();



            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

            return back();


        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }

    public function MyEmailUpdate(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users,email,' . Auth::id(),
            'password' => 'required',
        ]);
        try {

            $user = Auth::user();

            if (Config::get('app.app_sync')) {
                Toastr::error('For demo version you can not change this !', trans('common.Failed'));
                return redirect()->back();
            } else {
                // $success = trans('lang.Password').' '.trans('lang.Saved').' '.trans('lang.Successfully');


                if (!Hash::check($request->password, $user->password)) {
                    Toastr::error(trans('student.Password Do not match'), trans('common.Failed'));
                    return redirect()->back();
                }

                $user->update([
                    'email' => $request->email
                ]);
                Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                return redirect()->back();

            }


        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }

    public function deposit(Request $request)
    {
        try {
            return view(theme('pages.deposit'), compact('request'));

        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }

    public function loggedInDevices()
    {
        try {
            return view(theme('pages.log_in_devices'));

        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }


    }

    public function logOutDevice(Request $request)
    {
        $login = UserLogin::findOrFail($request->id);

        if (Auth::user()->role_id != 1 && !Hash::check($request->password, auth()->user()->password)) {
            Toastr::error(trans('frontend.Your Password Doesnt Match'));
            return back();
        }


        if (!empty($login->api_token)) {
            DB::table('oauth_access_tokens')->where('id', '=', $login->api_token)->delete();
        }
        if (!permissionCheck('student.loginActivity')) {
            Auth::logoutOtherDevices($request->password);
        }
        $login->status = 0;
        $login->logout_at = Carbon::now();
        $login->save();

        Toastr::success(trans('frontend.Logged Out SuccessFully'));
        return back();
    }

    public function Invoice($id)
    {

        try {
            return view(theme('pages.myInvoices'), compact('id'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function subInvoice($id)
    {

        try {

            $enroll = SubscriptionCheckout::where('id', $id)
                ->where('user_id', Auth::user()->id)
                ->with('plan', 'user')->first();

            if ($enroll == null) {
                Toastr::error(trans('student.Invalid Invoice'), trans('common.Failed'));
                return redirect()->back();
            }
            return view(theme('pages.mySubInvoices'), compact('enroll'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function StudentApplyCoupon(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'total' => 'required'
        ]);

        try {

            $code = $request->code;
            $cart_type = $request->type;

            $final = 0;
            $tax = 0;
            $discount = 0;
            $couponUsed = 0;
            $tax_ship = 0;
            $resultMsg = trans('common.Something Went Wrong');

            $coupon = Coupon::where('code', $code)->whereDate('start_date', '<=', Carbon::now())
                ->whereDate('end_date', '>=', Carbon::now())->where('status', 1)->first();

            $couponApply = false;
            $total = $request->total;

            if ($cart_type == 'subscription') {
                if (!isModuleActive('Subscription')) {
                    return false;
                }
                $tracking = SubscriptionCart::where('user_id', Auth::id())->first()->tracking;
                $checkout = SubscriptionCheckout::where('tracking', $tracking)->first();
                if (empty($checkout)) {
                    $checkout = new SubscriptionCheckout();
                }
                $checkTrackingId = SubscriptionCheckout::where('tracking', $tracking)->where('coupon_id', $coupon)->first();

            } else if ($cart_type == 'membership') {
                $this->membershipCoupon($coupon);
            } else {
                $tracking = Cart::where('user_id', Auth::id())->latest()->first()?->tracking;
                $checkout = Checkout::where('tracking', $tracking)->first();
                if (empty($checkout)) {
                    $checkout = new Checkout();
                }
                if ($coupon) {
                    $coupon_id = $coupon->id;
                } else {
                    $coupon_id = 0;
                }
                $checkTrackingId = Checkout::where('tracking', $tracking)->where('coupon_id', (int)$coupon_id)->first();
            }


            if ($coupon) {
                $max_dis = $coupon->max_discount;
                $min_purchase = $coupon->min_purchase;
                $type = $coupon->type;
                $value = $coupon->value;

                if ($type == 0) {
                    $discount = (($total * $value) / 100);
                } else {
                    $discount = $value;
                }

                if ($discount >= $max_dis) {
                    $discount = $max_dis;
                }

                if (isModuleActive('Subscription')) {
                    $couponUsed += $coupon->loginUserTotalSubscriptionUsed();
                }
                $couponUsed += $coupon->loginUserTotalUsed();

                if ($coupon->limit != 0 && $coupon->limit <= $couponUsed) {
                    $resultMsg = trans('coupons.Already used this coupon');
                } else {
                    if ($checkTrackingId) {
                        $resultMsg = trans('coupons.Already used this coupon');
                    } elseif ($total < $min_purchase) {
                        $resultMsg = trans('frontend.Coupon Minimum Purchase Does Not Match');
                    } elseif ($discount > $total) {
                        $resultMsg = trans('coupons.Invalid Request');
                    } elseif ($coupon->category == 2 && count($checkout->carts) != 1) {
                        $resultMsg = trans('coupons.This coupon apply for single course');
                    } elseif ($coupon->category == 2 && $checkout->carts[0]->course_id != $coupon->course_id) {
                        $resultMsg = trans('coupons.This coupon is not valid for this course');
                    } elseif ($coupon->category == 3 && $coupon->coupon_user_id != $checkout->user_id) {
                        $resultMsg = trans('coupons.This coupon not for you');
                    } elseif ($cart_type == 'subscription' && $coupon->category != 1) {
                        $resultMsg = trans('frontend.Invalid Coupon');
                    } elseif (isModuleActive('Organization') && $coupon->isOrganizationCoupon()) {

                        foreach ($checkout->carts as $rc) {
                            if (!$rc->course->isOrganizationCourse()) {
                                $resultMsg = "This coupon only valid for " . $coupon->couponOrganization()->name . " Organization course";
                                break;
                            }
                            if ($coupon->couponOrganization()->id != $rc->course->courseOrganization()->id) {
                                $resultMsg = "This coupon only valid for " . $coupon->couponOrganization()->name . " Organization course";
                                break;
                            } else {
                                $couponApply = true;
                                $resultMsg = trans("frontend.Coupon Successfully Applied");
                            }
                        }
                    } else {
                        $couponApply = true;
                        $resultMsg = trans("frontend.Coupon Successfully Applied");
                    }

                }

            } else {
                $checkout->discount = 0;
                $checkout->coupon_id = null;
                $checkout->purchase_price = $request->total + $tax_ship;
                $checkout->save();
                $resultMsg = trans('frontend.Invalid Coupon');
            }

            if ($couponApply) {

                $final = ($total + $tax_ship - $discount);
                $checkout->discount = $discount;
                $checkout->purchase_price = $final;

                if (hasTax()) {
                    $tax = taxAmount($request->total);
                    $total = applyTax($request->total);
                    $checkout->tax = $tax;
                } else {
                    $total = $request->total;
                }

                $checkout->tracking = $tracking;
                $checkout->purchase_price = getPriceAsNumber($final);

                if (isModuleActive('UpcomingCourse')) {
                    $checkout->purchase_price -= $checkout->pre_booking_amount;
                }


                $checkout->user_id = Auth::id();
                $checkout->coupon_id = $coupon->id;
                $checkout->price = $total;
                $checkout->status = 0;
                $checkout->save();


                return response()->json([
                    'success' => $resultMsg,
                    'total' => number_format(getPriceAsNumber($final), 2),
                    'tax' => number_format(getPriceAsNumber($tax), 2),
                    'discount' => number_format(getPriceAsNumber($discount), 2)
                ]);
            } else {
                return response()->json([
                    'error' => $resultMsg,
                    'total' => number_format(getPriceAsNumber($total + $tax_ship), 2),
                    'tax' => number_format(getPriceAsNumber($tax), 2),
                ]);
            }


        } catch (Exception $e) {
            return response()->json(['error' => trans('common.Operation Failed')]);
        }
    }

    private function membershipCoupon($coupon)
    {
        if (!isModuleActive('Membership')) {
            return false;
        }
        $tracking = SubscriptionCart::where('user_id', Auth::id())->first()->tracking;
        $checkout = SubscriptionCheckout::where('tracking', $tracking)->first();
        if (empty($checkout)) {
            $checkout = new SubscriptionCheckout();
        }
        $checkTrackingId = SubscriptionCheckout::where('tracking', $tracking)->where('coupon_id', $coupon)->first();
    }

    public function CheckOut(Request $request)
    {
        if (onlySubscription()) {
            return redirect('/');
        }
        try {
            $carts = Cart::where('user_id', Auth::id())->count();
            $user = Auth::user();
            $certificate_order = session()->get('order_type');
            $invoice = session()->get('invoice');
            if ($carts == 0 && (!$certificate_order || !$invoice)) {
                return redirect('/');
            }

            if (isModuleActive('Org')) {
                $carts = Cart::where('user_id', Auth::id())->with('course', 'course.user')->get();
                $total = Cart::where('user_id', Auth::user()->id)->sum('price');
                if ($total == 0) {
                    foreach ($carts as $cart) {
                        if (!$cart->course->isLoginUserEnrolled) {
                            $enroll = new CourseEnrolled();
                            $enroll->user_id = \auth()->id();
                            $enroll->tracking = 1;
                            $enroll->course_id = $cart->course->id;
                            $enroll->purchase_price = 0;
                            $enroll->coupon = null;
                            $enroll->discount_amount = 0;
                            $enroll->status = 1;
                            $enroll->save();
                            $course = $cart->course;
                            $course->total_enrolled = $course->total_enrolled + 1;
                            $course->save();

                            if (isModuleActive('Chat')) {
                                event(new OneToOneConnection($course->user, $user, $course));
                            }
                            if (isModuleActive('Survey')) {
                                $hasSurvey = Survey::where('course_id', $course->id)->get();
                                foreach ($hasSurvey as $survey) {
                                    $surveyController = new SurveyController();
                                    $surveyController->assignSurvey($survey, $user);
                                }
                            }
                            if (isModuleActive('Affiliate')) {
                                if ($user->isReferralUser) {
                                    Event::dispatch(new ReferralPayment($user->id, $course->id, $cart->price));
                                }
                            }
                        }
                        $cart->delete();
                    }

                    Toastr::success(trans('org.Enrolled Successfully'), trans('common.Success'));
                    if (Session::exists('back')) {
                        return redirect(Session::get('back'));
                    } else {
                        
                        return redirect()->route('tenant.studentDashboard');
                    }
                }
            }

            $finalCart = Cart::select('course_id', 'price')->where('user_id', Auth::user()->id)->get();
            $this->postEvent([
                'name' => 'begin_checkout',
                'params' => [
                    $finalCart
                ],
            ]);

            return view(theme('pages.checkout'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function removeProfilePic()
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        try {
            $user = User::find(Auth::user()->id);
            $user->image = '';
            $user->save();

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();

        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function getCertificate($tenant_slug = '', $id, $slug, Request $request)
    {
        if (isModuleActive('CertificatePro') && Settings('use_certificate_template') == 'pro') {
            $course = Course::findOrFail($id);
            if (!empty($course->pro_certificate_id)) {
                $certificate = CertificateTemplate::find($course->pro_certificate_id);
            } else {
                if ($course->type == 1) {
                    $certificate = CertificateTemplate::where('default_for', 'c')->first();
                } elseif ($course->type == 2) {
                    $certificate = CertificateTemplate::where('default_for', 'q')->first();
                } elseif ($course->type == 3) {
                    $certificate = CertificateTemplate::where('default_for', 'l')->first();
                } else {
                    $certificate = null;
                }
            }
            if (!hasCourseValidAccess($course)) {
                return redirect()->back();
            }
            if (!$certificate) {
                Toastr::error(trans('certificate.Certificate Not Found!'));
                return back();
            } else {
                if (!$course->isLoginUserEnrolled) {
                    Toastr::error(trans('certificate.You Are Not Already Enrolled This course. Please Enroll It First'));
                    return back();
                }
                if ($course->type == 1) {
                    $percentage = round($course->loginUserTotalPercentage);
                    if ($percentage < 100) {
                        Toastr::error(trans('certificate.Please Complete The Course First'));
                        return back();
                    }
                } elseif ($course->type == 2) {
                    $quiz = QuizTest::where('course_id', $course->id)->where('pass', 1)->first();
                    if (!$quiz) {
                        Toastr::error(trans('certificate.You must pass the quiz'));
                        return back();
                    }
                } else {
                    $certificateCanDownload = false;
                    $totalClass = $course->class->total_class;
                    $completeClass = ClassComplete::where('user_id', Auth::id())->where('course_id', $course->id)->where('class_id', $course->class->id)->count();
                    if ($totalClass == $completeClass) {
                        $hasCertificate = $this->course->pro_certificate_id;
                        if (!empty($hasCertificate)) {
                            $certificate = CertificateTemplate::find($hasCertificate);
                            if ($certificate) {
                                $certificateCanDownload = true;
                            }
                        } else {
                            $certificate = CertificateTemplate::where('default_for', 'l')->first();
                            if ($certificate) {
                                $certificateCanDownload = true;
                            }
                        }
                    }
                    if (!$certificateCanDownload) {
                        Toastr::error(trans('certificate.You must attend live class'));
                        return back();
                    }
                }

                $certificate_record = CertificateRecord::where('student_id', Auth::user()->id)->where('course_id', $course->id)->first();
                $websiteController = new WebsiteController();
                $percentage = round($course->loginUserTotalPercentage);
                if (!$certificate_record && $percentage >= 100) {
                    $certificate_record = new CertificateRecord();
                    $certificate_record->certificate_id = $websiteController->generateUniqueCode();
                    $certificate_record->student_id = Auth::user()->id;
                    $certificate_record->course_id = $course->id;
                    $certificate_record->created_by = Auth::user()->id;
                    $certificate_record->save();
                }
                return redirect()->route('certificate_pro.student_certificate', [$certificate->id, 'course' => $course->id, 'c_id' => $certificate_record->certificate_id, 'u_id' => Auth::id()]);
            }

        }


        $course = Course::findOrFail($id);
        if (!empty($course->certificate_id)) {
            $certificate = Certificate::find($course->certificate_id);
        } else {
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

        if (!hasCourseValidAccess($course)) {
            return redirect()->back();
        }

        if (!$certificate) {
            Toastr::error(trans('certificate.Right Now You Cannot Download The Certificate'));
            return back();
        }

        if (!$course->isLoginUserEnrolled) {
            Toastr::error(trans('certificate.You Are Not Already Enrolled This course. Please Enroll It First'));
            return back();
        }
        if ($course->type == 1) {
            $percentage = round($course->loginUserTotalPercentage);
            if ($percentage < 100) {
                Toastr::error(trans('certificate.Please Complete The Course First'));
                return back();
            }
        } elseif ($course->type == 2) {
            $quiz = QuizTest::where('course_id', $course->id)->where('pass', 1)->first();
            if (!$quiz) {
                Toastr::error(trans('certificate.You must pass the quiz'));
                return back();
            }
        } else {
            $certificateCanDownload = false;
            $totalClass = $course->class->total_class;
            $completeClass = ClassComplete::where('course_id', $course->id)->where('class_id', $course->class->id)->count();
            if ($totalClass == $completeClass) {
                $hasCertificate = $course->certificate_id;
                if (!empty($hasCertificate)) {
                    $certificate = Certificate::find($hasCertificate);
                    if ($certificate) {
                        $certificateCanDownload = true;
                    }
                } else {
                    $certificate = Certificate::where('for_class', 1)->first();
                    if ($certificate) {
                        $certificateCanDownload = true;
                    }
                }
            }
            if (!$certificateCanDownload) {
                Toastr::error(trans('certificate.You must attend live class'));
                return back();
            }
        }


        $title = "{$course->slug}-certificate-for-" . Auth::user()->name . ".jpg";

        $downloadFile = new CertificateController();
        $websiteController = new WebsiteController();
        try {
            earnCourseBadge($course->id, auth()->id(), $course->has_badge);

            $certificate_record = $websiteController->getCertificateRecord($course->id);

            $request->certificate_id = $certificate_record ? $certificate_record->certificate_id : '';
            $request->course = $course;
            $request->user = Auth::user();
            $certificate = $downloadFile->makeCertificate($tenant_slug = '', $certificate->id, $request)['image'] ?? '';

            if (!$certificate){
                return '';
            }

            if (Settings('frontend_active_theme') == 'tvt' && empty(\request('download'))) {
                $url = $certificate->toPng()->toDataUri();
                return view(theme('pages.certificate-preview'), compact('url', 'course'));
            }

            $certificate= $certificate->toJpeg();
            $headers = [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'attachment; filename=' . $title,
            ];
            return response()->stream(function () use ($certificate) {
                echo $certificate;
            }, 200, $headers);

        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }

    }

    public function submitReview(Request $request)
    {
        Session::flash('selected_tab', 'review');
        $this->validate($request, [
            'review' => 'required',
            'rating' => 'required'
        ]);

        try {
            $user_id = Auth::id();

            $review = CourseReveiw::where('user_id', $user_id)->where('course_id', $request->course_id)->first();
            $course = Course::find($request->course_id);

            if (!hasCourseValidAccess($course)) {
                return redirect()->back();
            }

            if (is_null($review)) {

                $newReview = new CourseReveiw();
                $newReview->user_id = $user_id;
                $newReview->course_id = $request->course_id;
                $newReview->comment = $request->review;
                $newReview->star = $request->rating;
                $newReview->status = (int) Settings('topic_review_auto_approval');
                $newReview->save();

                $total = CourseReveiw::where('course_id', $course->id)->where('status', 1)->sum('star');
                $count = CourseReveiw::where('course_id', $course->id)->where('status', 1)->count();
                $average = $count > 0 ? $total / $count : 0;
                $course->reveiw = $average;
                $course->total_rating = $average;
                $course->save();
                (new CourseSettingController())->updateTotalCountForCourse($course);


                $course_user = User::findOrFail($course->user_id);
                $user_courses = Course::where('user_id', $course_user->id)->get();
                $user_total = 0;
                $user_rating = 0;
                foreach ($user_courses as $u_course) {
                    $total = CourseReveiw::where('course_id', $u_course->id)->where('status', 1)->sum('star');
                    $count = CourseReveiw::where('course_id', $u_course->id)->where('status', 1)->count();
                    if ($total != 0) {
                        $user_total = $user_total + 1;
                        $average = $count > 0 ? $total / $count : 0;
                        $user_rating = $user_rating + $average;
                    }
                    (new CourseSettingController())->updateTotalCountForCourse($u_course);

                }
                if ($user_total != 0) {
                    $user_rating = $user_rating / $user_total;
                }
                $course_user->total_rating = (int)$user_rating;
                $course_user->save();


                checkGamification('each_review', 'rating', $course_user);

                $this->sendNotification('Course_Review', $course->user, [
                    'time' => Carbon::now()->format('d-M-Y, g:i A'),
                    'course' => $course->getTranslation('title', $course->user->language_code ?? config('app.fallback_locale')),
                    'review' => $newReview->comment,
                    'star' => $newReview->star,
                ], [
                    'actionText' => trans('common.View'),
                    'actionUrl' => courseDetailsUrl(@$tenant_slug = null,@$course->id, @$course->type, @$course->slug),
                ]);


                if (isModuleActive('Org')) {
                    addOrgRecentActivity(\auth()->id(), $course->id, 'Review');
                }
                Toastr::success(trans('student.Review Submit Successfully'), trans('common.Success'));
                return redirect()->back();
            } else {

                Toastr::error(trans('student.Invalid Action'), trans('common.Failed'));
                return redirect()->back();
            }
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }

    }

    public function myReports(Request $request)
    {
        try {
            return view(theme('pages.myReports'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function enrollmentCancellation(Request $request)
    {
        try {
            return view(theme('pages.enrollmentCancellation'), compact('request'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function enrollmentCancellationSubmit(Request $request)
    {
       $rules = [
           'course' => 'required',
           'reason' => 'required',
       ];
        $request->validate($rules, validationMessage($rules));

        $row = CourseEnrolled::find($request->course);

        $user = Auth::user();
        $cancel = new CourseCanceled();
        $cancel->user_id = $user->id;
        $cancel->reason = $request->reason;
        $cancel->enroll_id = $request->course;
        $cancel->request_from = $user->role->name;
        $cancel->request_by = $user->id;
        $cancel->status = 0;
        $cancel->refund = 1;
        $cancel->course_id = $row->course_id;
        $cancel->purchase_price = $row->purchase_price;
        $cancel->save();


        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function leaderboard(Request $request)
    {
        checkGamification('each_review', 'rating');

        try {
            $type = $request->type;
            $limit = $request->get('limit');
            $orderBy = 'gamification_total_points';
            $query = User::select('id', 'name', 'image', 'gamification_points', 'gamification_total_points', 'user_level');
            if ($limit) {
                $query->limit($limit);
                $data['modal'] = 1;
            } else {
                $data['modal'] = 0;
            }

            if ($type == 'level') {
                $orderBy = 'user_level';
            } elseif ($type == 'badge') {
                $query->withCount('userBadges');
                $orderBy = 'user_badges_count';
            } elseif ($type == 'courses') {
                $orderBy = 'student_courses_count';
                $query->withCount('studentCourses');
            } elseif ($type == 'certificate') {
                $orderBy = 'certificate_records_count';
                $query->withCount('certificateRecords');
            }
            $data['students'] =
                $query->where('status', 1)
                    ->where('role_id', 3)
                    ->where('teach_via', 1)
                    ->when(\request()->get('institute'), function ($q) {
                        $q->where('institute_id', \request()->get('institute'));
                    })
                    ->when(\request()->get('level'), function ($q) {
                        $q->whereHas('studentCourses', function ($q) {
                            $q->whereHas('course', function ($q) {
                                $q->where('level', \request()->get('level'));
                            });
                        });
                    })
                    ->when(\request()->get('course'), function ($q) {
                        $q->whereHas('studentCourses', function ($q) {
                            $q->where('course_id', \request()->get('course'));
                        });
                    })
                    ->when($type == 'badge', function ($q) {
                        $q->orderBy('user_badges_count', 'desc');
                    })
                    ->orderBy($orderBy, 'desc')
                    ->get();

            if ($type == 'show_badge') {
                $data['student'] =
                    User::select('id', 'name', 'image', 'gamification_points', 'gamification_total_points', 'user_level')
                        ->whereHas('userBadges.badge', function ($q) {
                            $q->where('status', 1);
                        })
                        ->with('userBadges', 'userBadges.badge')
                        ->withCount('userBadges')
                        ->where('id', $request->id)->first();
            }
            $data['type'] = $type;
            return view(theme('partials._leaderboard'), $data);

        } catch (Exception $exception) {
            return '';
        }
    }

    public function rewardPontConvert()
    {
        if (demoCheck()) {
            return redirect()->back();
        }

        if (Settings('gamification_status') && Settings('gamification_reward_point_conversion_status') && Settings('gamification_reward_status')) {
            $user = Auth::user();
            $total = $user->gamification_total_points;
            $spent = $user->gamification_total_spent_points;
            $current = $total - $spent;

            if ($current < 1) {
                Toastr::error(trans('frontend.Insufficient Point'), trans('common.Failed'));
                return redirect()->back();
            }

            $user->gamification_total_spent_points = $spent + $current;

            $user->save();

            UserGamificationPoint::create([
                'user_id' => $user->id,
                'type' => 'convert',
                'badge_type' => 'reward',
                'point' => $current,
                'status' => 2,
            ]);


            $balance = $current / Settings('gamification_reward_point_conversion_rate');

            $tran = new OfflinePayment();
            $new = $user->balance + $balance;
            $tran->user_id = $user->id;
            $tran->role_id = $user->role_id;
            $tran->amount = $balance;
            $tran->status = 1;
            $tran->type = 'Reward';
            $tran->after_bal = $new;
            $tran->save();
            $user->balance = $new;
            $user->save();

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

        } else {
            Toastr::error(trans('common.Something Went Wrong'), trans('common.Failed'));
        }


        return redirect()->back();
    }

    public function myBadges()
    {
        try {
            return view(theme('pages.myBadge'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function my_purchase_order_detail($id)
    {
        try {

            return view(theme('pages.myOrderDetails'), compact('id'));

        } catch (Exception $e) {

            return back();
        }
    }

    public function downloadVirtualFile($slug)
    {
        try {
            $course = Course::where('slug', $slug)->first();
            $product = Product::find($course->product_id);
            $file_path = $product->soft_file;

            return response()->download($file_path);
        } catch (Exception $e) {
            return back();
        }
    }

    public function make_refund_request($id)
    {
        return view(theme('pages.myOrderRefundRequest'), compact('id'));
    }

    public function store_refund_request(Request $request)
    {
         if ($request->product_images_) {
            foreach ($request->product_images_ as $product_img) {
                $request->validate([
                    'product_images_' . $product_img . '.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,tif,bmp,ico,psd,webp',
                ]);
            }
        }

        if (empty($request->product_ids)) {
            Toastr::error(__('product.select_product_first'));
            return back();
        }
        DB::beginTransaction();
        try {
            $data = $request->all();

            $package = OrderPackageDetail::find($data['package_id']);
            $shippingMethod = null;
            if ($package) {
                $shippingMethod = ShippingMethod::find($package->shipping_method);
            } else {
                Toastr::error(trans('frontend.Invalid Request'), trans('common.Failed'));
                return redirect()->back();
            }

            $total_return_amount = 0;

            $refund_request = new RefundRequest();
            $refund_request->customer_id = auth()->user()->id;
            $refund_request->order_id = $data['order_id'];
            $refund_request->refund_method = $data['money_get_method'];
            $refund_request->shipping_method = $data['shipping_way'];
            if ($data['shipping_way'] == "courier") {
                $refund_request->shipping_method_id = $shippingMethod->id;
                $refund_request->pick_up_address_id = $data['pick_up_address_id'];
            } else {
                $refund_request->shipping_method_id = $shippingMethod->id;
                $refund_request->drop_off_address = $data['drop_off_courier_address'];
            }
            $refund_request->additional_info = $data['additional_info'];
            $refund_request->save();

            if (@$data['product_images_']) {
                foreach ($data['product_images_'] as $image) {
                     if ($image) {
                        ImageRefundRequest::create([
                            'refund_request_id' => $refund_request->id,
                            'image' => $this->saveImage($image),
                        ]);
                    }
                }
            }

            // till this end

            if ($data['money_get_method'] == "bank_transfer") {
                StoreBankPayment::create([
                    'itemable_id' => $refund_request->id,
                    'itemable_type' => RefundRequest::class,
                    'bank_name' => $data['bank_name'],
                    'branch_name' => $data['branch_name'],
                    'account_number' => $data['account_no'],
                    'account_holder' => $data['account_name'],
                ]);
            }
            foreach ($data['product_ids'] as $key => $send_product_id) {
                $split = explode('-', $send_product_id);
                 $package[$key] = $split[0];
                $product[$key] = $split[1];
                $amount[$key] = $split[2];
                $course[$key] = $split[3];
                $request_detail_info = [
                    "refund_request_id" => $refund_request->id,
                    "order_package_id" => $package->id,
                    "seller_id" => $package->seller->id
                ];
                $refund_request_details = RefundRequestDetail::updateOrCreate($request_detail_info);
                $request_product_info = [
                    'refund_request_detail_id' => $refund_request_details->id,
                    'product_id' => $product[$key],
                    'refund_reason_id' => $data['reason_id'][$key],
                    'return_qty' => $data['qty'][$key],
                    'return_amount' => $amount[$key] * $data['qty'][$key],


                ];
                $request_product = RefundProduct::Create($request_product_info);
                $total_return_amount += $request_product->return_amount;
            }
            $shipping_configuration = ShippingConfiguration::where('seller_id', $package->seller->id)->first();
            $refund_quantity = $refund_request_details->refund_products->sum('return_qty');
            $package_product_qty = $package->products->sum('qty');
            if ($refund_quantity == $package_product_qty) {
                // if ($shipping_configuration->shipping_amount_back_refund_complete) {
                // $shipping_cost =  $package->shipping_cost;
                // }else{
                $shipping_cost = 0;
                // }
                if (!isModuleActive('Store')) {
                    $refund_request->update([
                        'total_return_amount' => $total_return_amount + $shipping_cost
                    ]);
                } else {
                    $refund_request->update([
                        'total_return_amount' => $total_return_amount + $shipping_cost + $package->tax_amount
                    ]);
                }
            } else {
                $refund_request->update([
                    'total_return_amount' => $total_return_amount
                ]);
            }

            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->route('myRefundDispute');
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error(__('common.Something Went Wrong'));
            return back();
        }
    }

    public function myRefundDispute()
    {
        try {
            return view(theme('pages.myRefundDispute'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function my_refund_show($id)
    {
        try {
            return view(theme('pages.myRefundDisputeDetails'), compact('id'));
        } catch (Exception $e) {
            return back();
        }
    }

    public function my_purchase_order_pdf($id)
    {
        try {
            $order = Checkout::find(decrypt($id));
            return view('products.customer_invoice', compact('order'));
        } catch (Exception $e) {
            return back();
        }
    }

    public function my_virtual_file_download($id)
    {
        try {
            return view(theme('pages.myVirtualFileDownload'), compact('id'));
        } catch (Exception $e) {
            return back();
        }
    }

    public function paymentSuccess($data)
    {

        $decodedValue = json_decode(Crypt::decryptString($data), true);

        if ($decodedValue['type'] == 'checkout') {

            $checkout = Checkout::with('user','courses')->findOrFail($decodedValue['id']);

            return view(theme('pages.payment-success'),compact('checkout'));

        }
        return back();

    }

    public function StudentApplyCourseCoupon(Request $request)
    {

        $course =Course::find($request->id);
        if (!$course) {
            return response([
                'status' => false,
                'message' => trans('coupons.Invalid item'),
            ]);
        }

        $coupon = Coupon::where('code', $request->code)
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->where('status', 1)
            ->where('course_id', $request->id)
            ->first();
        if (!$coupon) {
            return response([
                'status' => false,
                'message' => trans('coupons.Coupon code invalid'),
            ]);
        }


        $total= $course->discount_price?$course->discount_price:$course->price;
        $max_dis = $coupon->max_discount;
        $min_purchase = $coupon->min_purchase;
        $type = $coupon->type;
        $value = $coupon->value;

        if ($type == 0) {
            $discount = (($total * $value) / 100);
        } else {
            $discount = $value;
        }

        if ($discount >= $max_dis) {
            $discount = $max_dis;
        }

        if (Auth::check()){
            $couponUsed = $coupon->loginUserTotalUsed();
        }else{
            $couponUsed = $coupon->totalUsed()->count();
        }

        if ($coupon->limit != 0 && $coupon->limit <= $couponUsed) {
            return response([
                'status' => false,
                'message' => trans('coupons.Already used this coupon'),
            ]);
        }


        if ($total < $min_purchase) {
            return response([
                'status' => false,
                'message' => trans('frontend.Coupon Minimum Purchase Does Not Match'),
            ]);
        } elseif ($discount > $total) {
            return response([
                'status' => false,
                'message' => trans('coupons.Invalid Request'),
            ]);
        }

        $final_total = $total - $discount;

        if (Auth::check()){
            Cart::where('course_id', $course->id)
                ->where('user_id',Auth::id())->update([
                    'coupon_id'=>$coupon->id,
                    'price'=>$final_total
                ]);
        }

        $coupon = [
            $course->id => [
                "coupon_id" => $coupon->id,
                "price" => $final_total,
                "code" => $request->code,
                "off" => $type==0?$value.'%':getPriceFormat($value),
            ]
        ];
        session()->put('coupon', $coupon);

        $result['status'] =true;
        $result['price'] = getPriceFormat($final_total);
        $result['code'] = $request->code;
        $result['off'] = $type==0?$value.'%':getPriceFormat($value);
        $result['message']=trans('coupons.Coupon Applied Successfully');

        return response($result);
    }

    public function StudentCancelCourseCoupon($id)
    {
        $course =Course::find($id);
        if (!$course) {
            Toastr::error(trans('coupons.Invalid item'));
            return back();
        }
        $total= $course->discount_price?$course->discount_price:$course->price;


        if (Auth::check()){
            Cart::where('course_id', $course->id)
                ->where('user_id',Auth::id())->update([
                    'coupon_id'=>null,
                    'price'=>$total
                ]);
        }
        $coupon = session()->get('coupon');
        if (isset($coupon[$course->id])) {
            unset($coupon[$course->id]);
            session()->put('coupon', $coupon);
        }

        Toastr::success(trans('coupons.Coupon removed Successfully'));
        return back();


    }

    public function courseBadges()
    {
        $badges = CourseBadge::where('user_id',Auth::id())->with('user:id,name,username','course:id,course_badge,title')->get();
        return view(theme('pages.courseBadges'),compact('badges'));
    }

    public function learningPathPrivateCourseGroup($tenant_slug=null, $id)
    {
        try {
            return view(theme('pages.learning_path_private_course_group'), compact('id'));
           
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }

    }

    public function privatePathCourseEnrolled($tenant_slug = null, $learning_path_id)
    {
        try {
            DB::beginTransaction();

            $learningPathCourses = LearningPathCourse::where('learning_path_id', $learning_path_id)
                ->pluck('course_id');

            $user = Auth::id();

            foreach ($learningPathCourses as $courseId) {

                $courseEnrolled = CourseEnrolled::where('user_id', $user)
                    ->where('course_id', $courseId)
                    ->first();

                if (!$courseEnrolled) {
                    $courseEnrolled = new CourseEnrolled();
                    $courseEnrolled->user_id = $user;
                    $courseEnrolled->tracking = $tracking ?? getTrx();
                    $courseEnrolled->course_id = $courseId;
                    $courseEnrolled->purchase_price = 0;
                    $courseEnrolled->coupon = 0;
                    $courseEnrolled->discount_amount = 0;
                    $courseEnrolled->status = 1;
                    $courseEnrolled->lms_id = 1;
                    $courseEnrolled->is_learning_path_course = 2;
                    $courseEnrolled->save();
                }
            }

            DB::commit();

            return redirect()
                ->route('private-path-course-enrolled', $learning_path_id)
                ->with('message', trans('frontend.Enrolled Successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function learningPlanList($tenant_slug = null, $id)
    {
        
    }

    public function userAnnouncements($tenant_slug = null, $id)
    {
        // print_r($id); exit;
        $announcements = announcementUser::where('user_id', $id)
                        ->with('announcement:id,title')
                        ->get();
        // print_r($announcements); exit;

        return view(theme('pages.userannouncements'),compact('announcements'));
    }

    public function userFeedbacks($tenant_slug = null, $id)
    {
        // print_r($id); exit;
        $feedbackallocations = FeedbackAllocation::where('user_id', $id)
                        ->with('feedback:id,feedback_name')
                        ->get();
        // print_r($announcements); exit;

        return view(theme('pages.userfeedbacks'),compact('feedbackallocations'));
    }

    // feedbacks.list

    public function userFeedBackForm($tenant_slug = null, $id)
    {
        // print_r($id); exit;
        $feedbackallocations = FeedbackAllocation::where('id', $id)
                        ->with('feedback:id,feedback_name')
                        ->first();
        // print_r($announcements); exit;

        return view(theme('pages.userfeedbackform'),compact('feedbackallocations'));
    }

    public function feedbackQuestionsAjax($id)
    {

        $questions = [

            [
                'id' => 1,
                'type' => 'rating',
                'question' => 'How likely is it that you would recommend this course to a colleague?'
            ],

            [
                'id' => 2,
                'type' => 'rating',
                'question' => 'The trainer/facilitator has a good knowledge and understanding of the topic'
            ],

            [
                'id' => 3,
                'type' => 'likert',
                'question' => 'The trainer/facilitator has a good knowledge and understanding of the topic'
            ],

            [
                'id' => 4,
                'type' => 'likert',
                'question' => 'The trainer/facilitator is clear in their communication and presentation'
            ],

            [
                'id' => 5,
                'type' => 'likert',
                'question' => 'The instructor is willing to clarify queries and answer questions'
            ],

            [
                'id' => 6,
                'type' => 'likert',
                'question' => 'The handouts, audio-visuals used are useful'
            ],

            [
                'id' => 7,
                'type' => 'likert',
                'question' => 'The content is relevant to your day to day work'
            ],

            [
                'id' => 8,
                'type' => 'likert',
                'question' => 'Your expectations from the program were met'
            ]

        ];

        return response()->json([

            'questions' => $questions

        ]);
    }

   
    public function uploadUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return response()->json([
                    'success' => true,
                    'message' => 'Users updated successfully'
                ]);
    }

    public function corporateList()
    {
        $id = Auth::id();

        $profiles = profile::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('profile_name', 'asc')->get();
       
        $businessUnits = BusinessUnit::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('bu_name', 'asc')->get();

        $departments = Department::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('dept_name', 'asc')->get();

        // $countries = AssociateCountry::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('country_name', 'asc')->get();

        // $states = AssociateState::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('state_name', 'asc')->get();
        
        // $cities = AssociateCity::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('city_name', 'asc')->get();
        
        // $managements = Management::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('management_name', 'asc')->get();

        $locations = LocationCode::where(['organization_id' => Auth::user()->organization_id, 'status' => 1])->orderBy('location_code', 'asc')->get();
        
        $primaryskills = Skills::select('id','name')->where('type', 1)->get();
        $secondaryskills = Skills::select('id','name')->where('type', 2)->get();


        return view(theme('pages.corporate_list'), compact('profiles', 'primaryskills', 'secondaryskills', 'businessUnits', 'departments', 'locations'));
    }

    public function getSecondarySkills($tenant_slug = null, $id)
    {
        // Step 1: Get selected skill
        $primarySkill = CorporateSkill::find($id);

        if (!$primarySkill) {
            return response()->json([]);
        }

        // Step 2: Get all skills in same category (except selected one)
        $skills = CorporateSkill::select('id', 'name')
            ->where('category', $primarySkill->category)
            ->where('id', '!=', $id) // optional (exclude primary)
            ->get();

        return response()->json($skills);
    }



   

    public function corporateUsers($tenant_slug, $id)
    {
        $tenant = Tenants::findOrFail($id);

        $users = User::where('organization_id', $id)
            ->where('status', 2)
            ->get();

        return view(theme('pages.org_resource_list'), compact('tenant', 'users'));
    }


        // =========================================
        // USERS DATATABLE
        // =========================================

    public function corporateUsersDatatable(Request $request, $tenant_slug = null, $id)
    {
        // print_r($id); exit;
        // $id = Auth::user()->organization_id;
        $query = User::select(
                'id',
                'name',
                'email',
                'phone',
                'status'
            )

            ->where('organization_id', $id)

            ->where('status', 2);

            return DataTables::eloquent($query)

                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) {

                    return '
                        <input type="checkbox"
                            class="userCheckbox"    
                            value="'.$row->id.'">
                    ';
                })

                ->addColumn('name', function ($row) {

                    return $row->name;
                })
                
                 ->addColumn('email', function ($row) {

                    return $row->email;
                })


                 ->addColumn('phone', function ($row) {

                    return $row->phone ?? '-';
                })

                ->addColumn('status_badge', function ($row) {

                    if ($row->status == 1) {

                        return '
                            <span class="badge bg-success">

                                Active

                            </span>
                        ';
                    }

                    return '
                        <span class="badge bg-info">

                            Available Resource

                        </span>
                    ';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <button
                            class="theme_btn small viewUserBtn"
                            data-id="'.$row->id.'">

                            VIEW

                        </button>
                    ';
                })

                ->rawColumns([
                    'checkbox',
                    'status_badge',
                    'action'
                ])

                ->make(true);

        
    }

    public function userDetails($tenant_slug, $id)
    {
        $user = User::findOrFail($id);

        return response()->json([

            'name' => $user->name ?? '-',

            'email' => $user->email ?? '-',

            'phone' => $user->phone ?? '-',
            'status' => $user->status,

            'department' => optional($user->department)->dept_name ?? '-',

            'primary_skill' => optional($user->primarySkill)->name ?? '-',

            'secondary_skill' => optional($user->secondarySkill)->name ?? '-',

            'joined_date' => optional($user->created_at)
                                ->format('d M Y'),

            'resource_status' => 'Available',

            'image' => $user->image
                        ? asset($user->image)
                        : null
        ]);
    }

    public function sendTransferRequest(Request $request)
    {
        // print_r($request->all()); exit;
        $request->validate([
            'users' => 'required|array',
            'description' => 'nullable'
        ]);

        // =========================================
        // GET COMMON RECEIVER
        // =========================================

        $receiver = User::select('id', 'name', 'email')
            ->where('organization_id', $request->org_id)
            ->where(function ($q) {
                $q->where('is_hr', 1);
            })
            ->first();
        
        // return $receiver; 

        if (!$receiver) {

         $receiver = User::select('id', 'name', 'email')
            ->where('organization_id', $request->org_id)
            ->where(function ($q) {
                $q->where('is_hr', 0)
                ->orWhere('role_id', 11);
            })
            ->first();

            // return response()->json([
            //     'status' => false,
            //     'message' => 'Receiver not found'
            // ]);
        }

        // =========================================
        // STORE USERS
        // =========================================

        $allUsers = [];

        foreach ($request->users as $userId) {

            // =========================================
            // GET USER
            // =========================================

            $userDetail = User::find($userId);

            if (!$userDetail) {
                continue;
            }

            // =========================================
            // CHECK EXISTING REQUEST
            // =========================================

            $userExist = TransferResourceDetail::where('transfer_user_id', $userId)
                ->where('status', 2)
                ->first();

            if ($userExist) {
                continue;
            }

            // =========================================
            // CREATE TRANSFER
            // =========================================

            $transfer = TransferResourceDetail::create([
                'transfer_user_id' => $userId,
                'request_sender_id' => Auth::id(),
                'request_receiver_id' => $receiver->id,
                'description' => $request->description,
                'status' => 2
            ]);

            if (!$transfer) {
                continue;
            }

            // =========================================
            // UPDATE USER STATUS
            // =========================================
            storeActivity(

                'send_request',

                'Sent request to User ID : '.$receiver->id
            );

            $userDetail->update([
                'status' => 3
            ]);

            // =========================================
            // STORE USER FOR MAIL
            // =========================================

            $allUsers[] = $userDetail;
        }

        // =========================================
        // NO USERS
        // =========================================

        if (count($allUsers) == 0) {

            return response()->json([
                'status' => false,
                'message' => 'No users available for transfer'
            ]);
        }

        // =========================================
        // TENANT DETAILS
        // =========================================

        $tenant = Tenants::find(Auth::user()->organization_id);

        try {

            // =========================================
            // USER LIST HTML
            // =========================================

            $userList = '';

            foreach ($allUsers as $key => $transferUser) {

                $number = $key + 1;

                $userList .= '
                    <p style="margin-bottom:8px;">
                        ' . $number . '. 
                        <strong>' . $transferUser->name . '</strong>
                        (' . $transferUser->email . ')
                    </p>
                ';
            }

            // =========================================
            // SEND MAIL
            // =========================================

            user_transfer_request(
                $receiver,
                'User_Transfer_Request',
                [
                    'email' => $receiver->email,
                    'name' => $receiver->name,
                    'user_name' => $receiver->name,
                    'users_list' => $userList,
                    'description' => $request->description,
                    'sender_name' => Auth::user()->name,
                    'org_name' => $tenant->tenant_name ?? '-',
                    'contact_no' => $tenant->phone ?? '-',
                ]
            );

            // =========================================
            // UPDATE MAIL STATUS
            // =========================================

            foreach ($allUsers as $transferUser) {

                $transferUser->update([
                    'mail_status' => 1,
                    'mail_sent_at' => now()
                ]);
            }

        } catch (\Throwable $e) {

            \Log::error($e->getMessage());

            foreach ($allUsers as $transferUser) {

                $transferUser->update([
                    'mail_status' => 0,
                    'mail_sent_at' => null
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Users Transfer Requested Successfully'
        ]);
    }


    public function waitingList()
    {
         storeActivity(

            'page_visit',

            'Visited Available Resources Page'
        );
        
        $id = Auth::id();
       
        $profiles = Profile::select('id','profile_name')->where('status', 1)->get();

        return view(theme('pages.waiting_list'), compact('profiles'));
    }


    public function getOrganizationResourceLists(Request $request)
    {
        $organizationId = Auth::user()->organization_id;

        $query = User::select(
                'id as user_id',
                'name',
                'email',
                'experience',
                'status',
                'profile_id',
                'location_code'
            )

            /*
            |------------------------------------------------------------------
            | RELATIONSHIPS
            |------------------------------------------------------------------
            */

            ->with([
                'profile:id,profile_name',
                'locationCode:id,location_code'
            ])

            /*
            |------------------------------------------------------------------
            | FILTERS
            |------------------------------------------------------------------
            */

            ->where('organization_id', $organizationId)

            ->whereNotIn('status', [0, 1]);

        return DataTables::eloquent($query)

            ->addIndexColumn()

            /*
            |------------------------------------------------------------------
            | CHECKBOX
            |------------------------------------------------------------------
            */

            ->addColumn('checkbox', function ($row) {

                return '
                    <input type="checkbox"
                        class="userCheckbox"
                        value="' . $row->user_id . '">
                ';
            })

            /*
            |------------------------------------------------------------------
            | NAME
            |------------------------------------------------------------------
            */

            ->addColumn('name', function ($row) {

                return e($row->name);
            })

            /*
            |------------------------------------------------------------------
            | EMAIL
            |------------------------------------------------------------------
            */

            ->addColumn('email', function ($row) {

                return e($row->email);
            })

            /*
            |------------------------------------------------------------------
            | PROFILE
            |------------------------------------------------------------------
            */

            ->addColumn('profile', function ($row) {

                return optional($row->profile)->profile_name ?? '-';
            })

            /*
            |------------------------------------------------------------------
            | EXPERIENCE
            |------------------------------------------------------------------
            */

            ->addColumn('experience', function ($row) {

                return $row->experience ?? '0';
            })

            /*
            |------------------------------------------------------------------
            | LOCATION
            |------------------------------------------------------------------
            */

            ->addColumn('location', function ($row) {

                return optional($row->locationCode)->location_code ?? '-';
            })

            /*
            |------------------------------------------------------------------
            | STATUS BADGE
            |------------------------------------------------------------------
            */

            ->addColumn('status_badge', function ($row) {

                if ($row->status == 2) {

                    return '
                        <span class="badge bg-success">
                            Available
                        </span>
                    ';
                }

                if ($row->status == 3) {

                    return '
                        <span class="badge bg-warning">
                            Requested
                        </span>
                    ';
                }

                return '
                    <span class="badge bg-secondary">
                        Unknown
                    </span>
                ';
            })

            /*
            |------------------------------------------------------------------
            | ACTION
            |------------------------------------------------------------------
            */

            ->addColumn('detail', function ($row) {

                    return '
                        <a
                            href="' . route('corporateViewDetails', $row->user_id) . '"
                            class="theme_btn small">

                            VIEW

                        </a>
                    ';
                })

            ->addColumn('action', function ($row) {

                return '
                    <button
                        class="theme_btn small viewUserBtn"
                        data-id="' . $row->user_id . '">

                        VIEW

                    </button>
                ';
            })

            /*
            |------------------------------------------------------------------
            | RAW COLUMNS
            |------------------------------------------------------------------
            */

            ->rawColumns([
                'checkbox',
                'status_badge',
                'action'
            ])

            ->make(true);
    }

    public function requestList()
    {
        $profiles = Profile::select('id','profile_name')->where('status', 1)->get();

        return view(theme('pages.request_list'), compact('profiles'));
    }

    public function receivedRequestList()
    {
        $profiles = Profile::select('id','profile_name')->where('status', 1)->get();

        return view(theme('pages.received_request_list'), compact('profiles'));
    }


    public function getOrganizationReceivedRequestLists(Request $request)
    {
        $currentUserId = Auth::id();

        // dd($currentUserId);

        $query = TransferResourceDetail::query()

            ->with([

                'transferUser:id,name,email,phone,organization_id,experience,profile_id,location_code',

                'transferUser.TenantUser:id,tenant_name',

                'transferUser.profile:id,profile_name',

                'transferUser.locationCode:id,location_code'

            ])

            ->where(
                'request_receiver_id',
                $currentUserId
            )
            
            ->when($request->status, function ($q) use ($request) {

                $q->where('status', $request->status);

            })

            ->latest();
        
        // dd($query->mysql());

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('checkbox', function ($row) {

                    return '
                        <div class="table-checkbox">
                            <input type="checkbox"
                                class="custom-checkbox row-checkbox"
                                value="'.$row->id.'">
                        </div>
                    ';
                })

                ->addColumn('name', function ($row) {

                    return '
                        <div class="user-name">
                            '.$row->transferUser->name.'
                        </div>
                    ';
                })

                ->addColumn('tenant_name', function ($row) {

                    return $row->transferUser->TenantUser->tenant_name ?? '-';
                })

                ->addColumn('profile_name', function ($row) {

                    return $row->transferUser->profile->profile_name ?? '-';
                })

                ->addColumn('experience', function ($row) {

                    return $row->transferUser->experience
                        ? $row->transferUser->experience.' Years'
                        : '-';
                })

                ->addColumn('location', function ($row) {

                    return $row->transferUser->locationCode->location_code ?? '-';
                })

                ->addColumn('status_badge', function ($row) {

                    if($row->status == 2){

                        return '<span class="badge bg-warning">Pending</span>';
                    }

                    if($row->status == 1){

                        return '<span class="badge bg-success">Approved</span>';
                    }

                    return '<span class="badge bg-danger">Rejected</span>';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <div class="action-wrap">

                            <a href="javascript:void(0)"
                                class="action-btn view-btn">

                                <i class="ri-eye-line"></i>
                            </a>

                            <a href="javascript:void(0)"
                                class="action-btn delete-btn">

                                <i class="ri-delete-bin-6-line"></i>
                            </a>

                        </div>
                    ';
                })

                ->rawColumns([
                    'checkbox',
                    'name',
                    'status_badge',
                    'action'
                ])

            ->make(true);
    }
    
    public function getOrganizationRequestLists(Request $request)
    {
        $currentUserId = Auth::id();

        $query = TransferResourceDetail::query()

            ->with([

                'transferUser:id,name,email,phone,organization_id,experience,profile_id,location_code',

                'transferUser.TenantUser:id,tenant_name',

                'transferUser.profile:id,profile_name',

                'transferUser.locationCode:id,location_code'

            ])

            ->where(
                'request_sender_id',
                $currentUserId
            )

            ->when($request->status, function ($q) use ($request) {

                $q->where('status', $request->status);

            })

            ->latest();

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('checkbox', function ($row) {

                    return '
                        <div class="table-checkbox">
                            <input type="checkbox"
                                class="custom-checkbox row-checkbox"
                                value="'.$row->id.'">
                        </div>
                    ';
                })

                ->addColumn('name', function ($row) {

                    return '
                        <div class="user-name">
                            '.$row->transferUser->name.'
                        </div>
                    ';
                })

                ->addColumn('tenant_name', function ($row) {

                    return $row->transferUser->TenantUser->tenant_name ?? '-';
                })

                ->addColumn('profile_name', function ($row) {

                    return $row->transferUser->profile->profile_name ?? '-';
                })

                ->addColumn('experience', function ($row) {

                    return $row->transferUser->experience
                        ? $row->transferUser->experience.' Years'
                        : '-';
                })

                ->addColumn('location', function ($row) {

                    return $row->transferUser->locationCode->location_code ?? '-';
                })

                ->addColumn('status_badge', function ($row) {

                    if($row->status == 2){

                        return '<span class="badge bg-warning">Pending</span>';
                    }

                    if($row->status == 1){

                        return '<span class="badge bg-success">Approved</span>';
                    }

                    return '<span class="badge bg-danger">Rejected</span>';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <div class="action-wrap">

                            <a href="javascript:void(0)"
                                class="action-btn view-btn">

                                <i class="ri-eye-line"></i>
                            </a>

                            <a href="javascript:void(0)"
                                class="action-btn delete-btn">

                                <i class="ri-delete-bin-6-line"></i>
                            </a>

                        </div>
                    ';
                })

                ->rawColumns([
                    'checkbox',
                    'name',
                    'status_badge',
                    'action'
                ])

            ->make(true);
    }

    public function approveUsers(Request $request)
    {
        storeActivity(

            'revert_user',

            'Reverted selected users to active'
        );

        $request->validate([
            'users' => 'required|array',
            'description' => 'nullable'
        ]);

        $allUsers = [];

        $receiver = null;

        foreach ($request->users as $transferId)
        {
            /*
            |--------------------------------------------------------------------------
            | GET TRANSFER DETAIL
            |--------------------------------------------------------------------------
            */

            $transfer = TransferResourceDetail::find($transferId);

            $receiver = User::find($transfer->request_sender_id);

            // print_r($receiver); exit;

            if (!$transfer) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE TRANSFER STATUS
            |--------------------------------------------------------------------------
            */

            $transfer->update([
                'status' => 1
            ]);

            /*
            |--------------------------------------------------------------------------
            | GET USER DETAIL
            |--------------------------------------------------------------------------
            */

            $userDetail = User::find($transfer->transfer_user_id);

            if (!$userDetail) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE USER STATUS
            |--------------------------------------------------------------------------
            */

            $userDetail->update([
                'status' => 0
            ]);

            /*
            |--------------------------------------------------------------------------
            | RECEIVER DETAILS
            |--------------------------------------------------------------------------
            */

            

            /*
            |--------------------------------------------------------------------------
            | STORE USERS
            |--------------------------------------------------------------------------
            */

            $allUsers[] = $userDetail;
        }

        /*
        |--------------------------------------------------------------------------
        | NO USERS
        |--------------------------------------------------------------------------
        */

        if (count($allUsers) == 0)
        {
            return response()->json([
                'status' => false,
                'message' => 'No users available for transfer'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ORGANIZATION DETAILS
        |--------------------------------------------------------------------------
        */

        $tenant = Tenants::find(
            Auth::user()->organization_id
        );

        try {

            /*
            |--------------------------------------------------------------------------
            | USER LIST HTML
            |--------------------------------------------------------------------------
            */

            $userList = '';

            foreach ($allUsers as $key => $user)
            {
                $number = $key + 1;

                $userList .= '
                    <p style="margin-bottom:8px;">
                        '.$number.'.
                        <strong>'.$user->name.'</strong>
                        ('.$user->email.')
                    </p>
                ';
            }

            /*
            |--------------------------------------------------------------------------
            | SEND MAIL
            |--------------------------------------------------------------------------
            */

            if ($receiver)
            {
                transfer_request_accepted(
                    $receiver,
                    'transfer_request_accepted',
                    [
                        'email'       => $receiver->email,
                        'name'        => $receiver->name,
                        'user_name'   => $receiver->name,
                        'users_list'  => $userList,
                        'description' => $request->description ?? '-',
                        'sender_name' => Auth::user()->name ?? '-',
                        'org_name'    => $tenant->tenant_name ?? '-',
                        'contact_no'  => $tenant->phone ?? '-',
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE MAIL STATUS
            |--------------------------------------------------------------------------
            */

            foreach ($allUsers as $user)
            {
                $user->update([
                    'mail_status' => 1,
                    'mail_sent_at' => now()
                ]);
            }

        } catch (\Throwable $e) {

            \Log::error($e->getMessage());

            foreach ($allUsers as $user)
            {
                $user->update([
                    'mail_status' => 0,
                    'mail_sent_at' => null
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Users Transferred Successfully'
        ]);
    }
    

    public function getCorporateLists(Request $request)
    {
        $query = Tenants::select(
                    'id',
                    'tenant_name',
                    'tenant_email'
                )
                ->where('tenant_type', CORPORATE)
                ->where('id', '!=', Auth::user()->organization_id);

        /*
        |--------------------------------------------------------------------------
        | COMMON FILTER FUNCTION
        |--------------------------------------------------------------------------
        */

        $filterUsers = function ($q) use ($request)
        {
            $q->where('status', 2);

            // BUSINESS UNIT

            if (!empty($request->business_unit))
            {
                $q->whereIn('bu', $request->business_unit);
            }

            // PRIMARY SKILLS

            if (!empty($request->primary_skills))
            {
                $q->whereHas('skills', function ($skillQuery) use ($request)
                {
                    $skillQuery->whereIn(
                        'skills.id',
                        $request->primary_skills
                    );
                });
            }

            // SECONDARY SKILLS

            if (!empty($request->secondary_skills))
            {
                $q->whereHas('skills', function ($skillQuery) use ($request)
                {
                    $skillQuery->whereIn(
                        'skills.id',
                        $request->secondary_skills
                    );
                });
            }

            // EXPERIENCE

            if (!empty($request->experience))
            {
                $q->where(
                    'experience',
                    '>=',
                    $request->experience
                );
            }

            // LOCATION

            if (!empty($request->location))
            {
                $q->whereIn(
                    'location_code',
                    $request->location
                );
            }

            // KEYWORD

            if ($request->filled('keyword'))
            {
                $keyword = $request->keyword;

                $q->where(function ($subQ) use ($keyword)
                {
                    $subQ->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('email', 'LIKE', "%{$keyword}%")
                        ->orWhereHas('skills', function ($skillQ) use ($keyword)
                        {
                            $skillQ->where(
                                'name',
                                'LIKE',
                                "%{$keyword}%"
                            );
                        });
                });
            }

            // LOAD RELATION

            $q->with('skills:id,name');
        };

        /*
        |--------------------------------------------------------------------------
        | WAITING USERS
        |--------------------------------------------------------------------------
        */

        $query->with([
            'waitingUsers' => $filterUsers
        ]);

        /*
        |--------------------------------------------------------------------------
        | WAITING USER COUNT
        |--------------------------------------------------------------------------
        */

        $query->withCount([
            'waitingUsers as waiting_users_count' => $filterUsers
        ]);

        /*
        |--------------------------------------------------------------------------
        | ONLY TENANTS WITH USERS
        |--------------------------------------------------------------------------
        */

        $query->having('waiting_users_count', '>', 0);

        /*
        |--------------------------------------------------------------------------
        | SORT BY MATCHED USERS
        |--------------------------------------------------------------------------
        */

        $query->orderByDesc('waiting_users_count');

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $tenants = $query->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | MATCH PERCENTAGE
        |--------------------------------------------------------------------------
        */

        $tenants->getCollection()->transform(function ($tenant) use ($request)
        {
            $filters = [
                        'keyword'          => $request->keyword,
                        'business_unit'    => $request->business_unit,
                        'primary_skills'   => $request->primary_skills,
                        'secondary_skills' => $request->secondary_skills,
                        'experience'       => $request->experience,
                        'location'         => $request->location,
                    ];

            $tenant->view_url = route(
                        'corporateResourceDetailsUrl',
                        ['id' => $tenant->id]
                    ) . '?' . http_build_query($filters);

                            
            if ($tenant->waitingUsers)
            {
                $tenant->waitingUsers->transform(function ($user) use ($request)
                {
                    $totalFilters = 0;

                    $matchedFilters = 0;

                    // PRIMARY SKILLS

                    if (!empty($request->primary_skills))
                    {
                        $totalFilters++;

                        $userSkillIds = $user->skills
                            ->pluck('id')
                            ->toArray();

                        $matchedPrimary = array_intersect(
                            $request->primary_skills,
                            $userSkillIds
                        );

                        if (count($matchedPrimary) > 0)
                        {
                            $matchedFilters++;
                        }
                    }

                    // BUSINESS UNIT

                    if (!empty($request->business_unit))
                    {
                        $totalFilters++;

                        if (in_array($user->bu, $request->business_unit))
                        {
                            $matchedFilters++;
                        }
                    }

                    // LOCATION

                    if (!empty($request->location))
                    {
                        $totalFilters++;

                        if (in_array($user->location_code, $request->location))
                        {
                            $matchedFilters++;
                        }
                    }

                    // EXPERIENCE

                    if (!empty($request->experience))
                    {
                        $totalFilters++;

                        if ($user->experience >= $request->experience)
                        {
                            $matchedFilters++;
                        }
                    }

                    // FINAL MATCH %

                    if ($totalFilters > 0)
                    {
                        $user->match_percentage =
                            round(($matchedFilters / $totalFilters) * 100);
                    }
                    else
                    {
                        $user->match_percentage = rand(60, 95);
                    }

                    return $user;
                });
            }

            return $tenant;
        });

        return response()->json($tenants);
    }

    public function corporateResourceView(Request $request)
    {
        $query = User::query()

            ->where('status', 2)

            ->with([
                'skills:id,name',
                'TenantUser:id,tenant_name',
                'profile:id,profile_name'
            ]);

        /*
        |--------------------------------------------------------------------------
        | TENANT FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tenant_id'))
        {
            $query->where(
                'organization_id',
                $request->tenant_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | BUSINESS UNIT
        |--------------------------------------------------------------------------
        */

        if (!empty($request->business_unit))
        {
            $query->whereIn(
                'bu',
                $request->business_unit
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PRIMARY SKILLS
        |--------------------------------------------------------------------------
        */

        if (!empty($request->primary_skills))
        {
            $query->whereHas('skills', function ($q) use ($request)
            {
                $q->whereIn(
                    'skills.id',
                    $request->primary_skills
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | SECONDARY SKILLS
        |--------------------------------------------------------------------------
        */

        if (!empty($request->secondary_skills))
        {
            $query->whereHas('skills', function ($q) use ($request)
            {
                $q->whereIn(
                    'skills.id',
                    $request->secondary_skills
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | EXPERIENCE
        |--------------------------------------------------------------------------
        */

        if (!empty($request->experience))
        {
            $query->where(
                'experience',
                '>=',
                $request->experience
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOCATION
        |--------------------------------------------------------------------------
        */

        if (!empty($request->location))
        {
            $query->whereIn(
                'location_code',
                $request->location
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KEYWORD
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword'))
        {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword)
            {
                $q->where('name', 'LIKE', "%{$keyword}%")
                ->orWhere('email', 'LIKE', "%{$keyword}%");
            });
        }

        return datatables()

            ->of($query)

            ->addIndexColumn()

            /*
            |--------------------------------------------------------------------------
            | CHECKBOX
            |--------------------------------------------------------------------------
            */

            ->addColumn('checkbox', function ($user)
            {
                return '<input type="checkbox"
                        class="resource-checkbox"
                        value="'.$user->id.'"
                        data-name="'.$user->name.'">';

            })

            /*
            |--------------------------------------------------------------------------
            | NAME
            |--------------------------------------------------------------------------
            */

            ->editColumn('name', function ($user)
            {
                return '
                    <div class="resource-user">

                        <div class="resource-avatar">
                            <i class="ri-user-3-line"></i>
                        </div>

                        <div>

                            <div class="resource-name">
                                '.$user->name.'
                            </div>

                            <div class="resource-email">
                                '.$user->email.'
                            </div>

                        </div>

                    </div>';
            })

            /*
            |--------------------------------------------------------------------------
            | COMPANY
            |--------------------------------------------------------------------------
            */

            ->addColumn('company', function ($user)
            {
                return optional($user->tenant)->tenant_name ?? '-';
            })

            /*
            |--------------------------------------------------------------------------
            | ROLE / PROFILE
            |--------------------------------------------------------------------------
            */

            ->addColumn('role', function ($user)
            {
                return optional($user->profile)->profile_name ?? '-';
            })

            /*
            |--------------------------------------------------------------------------
            | SKILLS
            |--------------------------------------------------------------------------
            */

            ->addColumn('skills', function ($user)
            {
                return $user->skills
                    ->pluck('name')
                    ->map(function ($skill)
                    {
                        return '
                            <span class="skill-badge">
                                '.$skill.'
                            </span>';
                    })
                    ->implode(' ');
            })

            /*
            |--------------------------------------------------------------------------
            | EXPERIENCE
            |--------------------------------------------------------------------------
            */

            ->editColumn('experience', function ($user)
            {
                return $user->experience
                    ? $user->experience.' Years'
                    : '-';
            })

            ->addColumn('user_id', function ($user)
            {
                return $user->id;
            })

            /*
            |--------------------------------------------------------------------------
            | ACTION
            |--------------------------------------------------------------------------
            */

            ->addColumn('action', function ($user)
            {
                return '
                    <div class="action-dropdown">

                        <button class="action-btn dropdown-toggle-btn">
                            Action
                        </button>

                        <div class="action-dropdown-menu">

                            <a href="#"
                            class="dropdown-item">

                                <i class="ri-eye-line"></i>
                                View Details

                            </a>

                            <a href="#"
                            class="dropdown-item">

                                <i class="ri-send-plane-line"></i>
                                Send Request

                            </a>

                        </div>

                    </div>';
            })

            ->rawColumns([
                'checkbox',
                'name',
                'skills',
                'action'
            ])

            ->make(true);
    }

    public function corporateViewDetails($slug = null, $id)
    {
        // print_r($id); exit;
        $detail = User::select(
                        'id',
                        'name',
                        'experience',
                        'bu',
                        'profile_id',
                        'location_code',
                        'organization_id')
                    ->with([
                        'TenantUser:id,tenant_name',
                        'profile:id,profile_name',
                        'locationCode:id,location_code',
                        'skills:id,name'
                    ])
                    ->findOrFail($id);

            // print_r($detail); exit;
        return view(theme('pages.corporate_view_details'), compact('detail'));
    }

    public function corporateResourceDetails($id)
    {
        
        return view(theme('pages.corporate_resource_list')); 
    }

    public function sampleView()
    {
        return view(theme('pages.sample_view'));
    }

    public function revertBenchUsers(Request $request)
    {
        User::whereIn('id', $request->user_ids)
            ->update([
                'status' => 1
            ]);

        return response()->json([

            'success' => true,

            'message' => 'Users reverted successfully'
        ]);
    }

    public function myClasses()
    {
        return view(theme('pages.classes'));
    }

    public function getOnlineClasses(Request $request)
    {
        $query = CourseEnrolled::join('courses', 'courses.id', '=', 'course_enrolleds.course_id')
                ->join('lessons', 'lessons.course_id', '=', 'course_enrolleds.course_id')
                ->where('course_enrolleds.user_id', Auth::id())
                ->where('lessons.status', 0)
                ->whereIn('courses.course_type', [3])
                ->select(
                'course_enrolleds.id as enrollment_id',
                'courses.id as course_id',
                'lessons.id as lesson_id',
                'courses.title as course_name',
                'lessons.name as lesson_name',
                'lessons.start_date',
                'lessons.end_date',
                'lessons.link',
                'lessons.status'
            );
                

        //  dd($query->get()->toArray());

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('course_name', function ($row) {

                $title = json_decode($row->course_name, true);

                return $title['en'] ?? $row->course_name ?? '-';
            })

            ->addColumn('lesson_name', function ($row) {
                return $row->lesson_name ?? '-';
            })

            ->addColumn('start_date', function ($row) {
                return $row->start_date
                    ? date('d-m-Y', strtotime($row->start_date))
                    : '-';
            })

            ->addColumn('end_date', function ($row) {
                return $row->end_date
                    ? date('d-m-Y', strtotime($row->end_date))
                    : '-';
            })

            ->addColumn('meeting_url', function ($row) {

                if (!$row->link) {
                    return '-';
                }

                return '<a href="'.$row->link.'" target="_blank" class="theme_btn small">
                            Join Class
                        </a>';
            })

            ->addColumn('status_badge', function ($row) {

                return $row->status == 0
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Expired</span>';
            })

            ->rawColumns([
                'meeting_url',
                'status_badge',
            ])

            ->make(true);
    }

    public function bookmarkSave($tenant_slug = '', $id)
    {
        try {
            $bookmarked = BookmarkCourse::where('user_id', Auth::id())->where('course_id', $id)->first();
            if (empty($bookmarked)) {
                $bookmark = new BookmarkCourse;
                $bookmark->user_id = Auth::id();
                $bookmark->course_id = $id;
                $bookmark->date = date("jS F Y");
                $bookmark->save();

                $this->postEvent([
                    'name' => 'add_to_wishlist',
                    'params' => [
                        "items" => [
                            [
                                "item_id" => $id,
                                "item_name" => $bookmark->course?->title,
                            ]
                        ],
                    ],
                ]);

                Toastr::success(trans('frontend.Wishlist Added Successfully'), trans('common.Success'));
            } else {

                $this->postEvent([
                    'name' => 'remove_from_wishlist',
                    'params' => [
                        "items" => [
                            [
                                "item_id" => $id,
                                "item_name" => $bookmarked->course?->title,
                            ]
                        ],
                    ],
                ]);
                $bookmarked->delete();
                Toastr::success(trans('frontend.Wishlist Remove Successfully'), trans('common.Success'));

            }

            return redirect()->back();
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }

    }

    public function catalog(Request $request)
    {
        try {
            return view(theme('pages.catalog'), compact('request'));
        } catch (\Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

}
