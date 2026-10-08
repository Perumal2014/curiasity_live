<?php

namespace Modules\Organization\Http\Controllers;


use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\CourseSetting\Entities\Course;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\Organization\Entities\OrganizationFinance;
use Modules\Organization\Events\CourseSellCommissionEvent;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class FinancialReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('RoutePermissionCheck:organization.sales_report.index', ['only' => ['salesReport','salesReportDatatable']]);
        $this->middleware('RoutePermissionCheck:organization.financial_report.index', ['only' => ['financialReport','financialReportDatatable']]);
        $this->middleware('RoutePermissionCheck:organization.payout.index', ['only' => ['payout','payoutDatatable']]);
        $this->middleware('RoutePermissionCheck:organization.payout.store', ['only' => ['payoutSubmit']]);
        $this->middleware('RoutePermissionCheck:organization.payout.completed', ['only' => ['payoutCompleted']]);
    }

    public function payoutCompleted($id)
    {
        try {
            $row = OrganizationFinance::find($id);
            if ($row) {
                $row->update(['payment_type'=>OrganizationFinance::$payment_completed,'status'=>true]);
            }
            return response()->json([
                'msg' => trans('common.Operation successful'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }

    public function payoutSubmit(Request $request)
    {

        try {
            if((float) OrganizationFinance::readyToPayout() < (float) Settings('minimum_payout_amount')){
                Toastr::error('Minimum payout amount must be '.Settings('minimum_payout_amount'));
                return back();
            }

            DB::beginTransaction();
            $organization_commission_data = [
                'user_id'=>Auth::id(),
                'amount'=>(float) OrganizationFinance::readyToPayout(),
                'status'=>false,
                'type'=>OrganizationFinance::$debit,
                'description'=>OrganizationFinance::$payout_description,
                'data_type'=>OrganizationFinance::$type_payout,
                'payment_type'=>OrganizationFinance::$payment_pending,
            ];
            event( new CourseSellCommissionEvent($organization_commission_data));

            OrganizationFinance::where('user_id',Auth::id())
                ->where('data_type',OrganizationFinance::$type_income)
                ->where('type',OrganizationFinance::$credit)
                ->where('status',true)
                ->update(['payment_type'=>OrganizationFinance::$payment_completed]);

            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return back();
        }catch (Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage());
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }


    public function payout()
    {
        try {
            $data['account_balance'] = Auth::user()->balance;
            $data['total_income'] = OrganizationFinance::totalIncome();
            $data['ready_to_payout'] = OrganizationFinance::readyToPayout();
            $data['request_payout'] = OrganizationFinance::requestPayout();
            $data['total_payout'] = OrganizationFinance::totalPayout();
            $data['is_admin'] = false;
            if(Auth::user()->role_id == 1){
                $data['is_admin'] = true;
            }

            $data['users'] = User::where('role_id',5)->get();

            return view('organization::financial.payout.payout_report',$data);
        }catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }


    public function payoutDatatable(Request $request)
    {
        try {
            $is_admin = false;
            if(Auth::user()->role_id == 1){
                $is_admin = true;
            }
            $data = OrganizationFinance::query()->with(['user'])
                ->where('data_type',OrganizationFinance::$type_payout);

            if(!$is_admin){
                $data->where('user_id',Auth::id());
            }

            if ($request->f_date) {
                $data->whereBetween(DB::raw('DATE(created_at)'), formatDateRangeData($request->f_date));
            }
            if ($request->f_status) {
                $data->where('payment_type',$request->f_status);
            }

            if ($request->f_user) {
                $data->where('user_id',$request->f_user);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return showDate($row->created_at);
                })
                ->editColumn('amount', function ($row) {
                    return showPrice($row->amount);
                })
                ->addColumn('action', function ($row) {
                    $action_flag =  OrganizationFinance::$payment_pending == $row->payment_type?true:false;
                    return view('organization::financial.payout.components._action', ['row' => $row,'action_flag'=>$action_flag]);
                })
                ->editColumn('user', function ($row) {
                    return view('organization::financial._user_td', ['row' => $row->user]);
                })
                ->addColumn('payout_account', function ($row) {
                    return view('organization::financial.payout.components._payout_account', ['row' => $row->user]);
                })
                ->rawColumns(['user','action','payout_account'])
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }



    public function salesReport()
    {
        try {
            $data['is_admin'] = false;
            if(Auth::user()->role_id == 1){
                $data['is_admin'] = true;
            }
            $data['users'] = User::where('role_id',5)->get();
            $course_query = Course::query();
            if(isModuleActive('Organization') && Auth::user()->isOrganization()){
                $course_query->whereHas('user', function ($q) {
                    $q->where('organization_id', Auth::id());
                    $q->orWhere('user_id', Auth::id());
                });


            }
            $data['topics'] =$course_query->get();
            return view('organization::financial.sales_report',$data);
        }catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function salesReportDatatable(Request $request)
    {
        try {

            $is_admin = false;
            if(Auth::user()->role_id == 1){
                $is_admin = true;
            }

            $data = CourseEnrolled::query()->with(['course','course.user','user']);

            if($is_admin){
                $data->whereHas('course.user',function ($q){
                    $q->where('organization_id','!=',null);
                    $q->orWhere('role_id', 5);
                });
            }else{
                $data->whereHas('course.user',function ($q){
                    $q->where('organization_id',Auth::id());
                    $q->orWhere('user_id', Auth::id());
                });
            }

            if ($request->f_user) {
                $data->whereHas('course.user',function ($q) use ($request){
                    $q->where('id',$request->f_user);
                });
            }

            if ($request->f_date) {
                $data->whereBetween(DB::raw('DATE(created_at)'), formatDateRangeData($request->f_date));
            }
            if ($request->f_type) {
                $data->whereHas('course',function ($q) use ($request){
                    $q->where('type',$request->f_type);
                });
            }
            if ($request->f_topic) {
                $data->whereHas('course',function ($q) use ($request){
                    $q->where('id',$request->f_topic);
                });
            }


            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return showDate($row->created_at);
                })
                ->addColumn('topic_title', function ($row) {
                    return $row->course->title;
                })
                ->addColumn('topic_type', function ($row) {
                    return $row->course->courseType();
                })
                ->editColumn('purchase_price', function ($row) {
                    return showPrice($row->purchase_price);
                })
                ->editColumn('discount_amount', function ($row) {
                    return showPrice($row->discount_amount);
                })
                ->editColumn('reveune', function ($row) {
                    return showPrice($row->reveune);
                })
                ->addColumn('admin_profit', function ($row) {
                    return showPrice($row->purchase_price - $row->reveune);
                })
                ->addColumn('student', function ($row) {
                    return view('organization::financial._user_td', ['row' => $row->user]);
                })
                ->addColumn('organization', function ($row) {
                    return view('organization::financial._user_td', ['row' => $row->course->courseOrganization()]);
                })
                ->rawColumns(['student','organization'])
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }



    public function financialReport()
    {
        try {
            return view('organization::financial.financial_report');
        }catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function financialReportDatatable(Request $request)
    {
        try {
            $is_admin = false;
            if(Auth::user()->role_id == 1){
                $is_admin = true;
            }

            $data = OrganizationFinance::query();

            if($is_admin){
                $data->where('user_id',0);
            }else{
                $data->where('user_id',Auth::id());
            }


            if ($request->f_date) {
                $data->whereBetween(DB::raw('DATE(created_at)'), formatDateRangeData($request->f_date));
            }
            if ($request->f_type) {
                $data->where('type',$request->f_type);
            }
            if (isset($request->f_status)) {
                if($request->f_status){
                    $data->where('status',true);
                }else{
                    $data->where('status',false);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return showDate($row->created_at);
                })
                ->editColumn('amount', function ($row) {
                    return showPrice($row->amount);
                })
                ->editColumn('status', function ($row) {
                    if($row->status){
                        return OrganizationFinance::$payment_completed;
                    }
                    return OrganizationFinance::$payment_pending;;
                })
                ->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 503);
        }
    }
}
