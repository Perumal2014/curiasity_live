<?php

namespace Modules\SystemSetting\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use App\Traits\UploadMedia;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use LogActivity;
use Modules\RolePermission\Entities\Role;
use Modules\SystemSetting\Entities\Staff;
use Modules\SystemSetting\Entities\StaffDocument;
use Modules\SystemSetting\Http\Requests\StaffRequest;
use Modules\SystemSetting\Http\Requests\StaffUpdateRequest;
use Modules\SystemSetting\Repositories\LeaveRepository;
use App\Imports\ImportRegularStaff;
use App\Imports\ImportStaff;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AuditLog;
use Modules\CourseSetting\Entities\CourseEnrolled;
use Modules\CourseSetting\Entities\Course;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserRoles;

class StaffController extends Controller
{
    //    use Notification;
    use UploadMedia;

    protected $userRepository, $leaveRepository, $payrollRepository, $applyLoanRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        LeaveRepository         $leaveRepository
        //        PayrollRepositoryInterface $payrollRepository
    )
    {
        $this->middleware(['auth', 'verified']);

        $this->userRepository = $userRepository;
        $this->leaveRepository = $leaveRepository;
    }

    public function index(Request $request)
    {
        try {
            $user   = Auth::user();
            $deptId = $user->staffDetail?->department_id;

            $query = Staff::query()
                ->with(['user.role', 'department'])
                ->latest();

            //print_r($deptId); exit;

            if (in_array($user->role_id, [7, 8, 11])) {
                $query->whereHas('user', function ($q) use ($user) {
                        $q->where('organization_id', $user->organization_id);
                    });
            } else if (in_array($user->user_type, [3, 4])) {
                $query->where('department_id', $deptId)
                    ->whereHas('user', function ($q1) use ($user) {
                        $q1->where('organization_id', $user->organization_id);
                    });
            }  

            // TYPE 1 & 2 → SEE ALL
            // if (in_array($user->user_type, [1, 2, 3])) {
            //     // only restrict by org if multi-tenant
            //     if ($user->organization_id) {
            //         $query->whereHas('user', function ($q) use ($user) {
            //             $q->where('organization_id', $user->organization_id);
            //         });
            //     }

            // }
            
            // TYPE 3 → ONLY DEPARTMENT
            // if ($user->type == 3) {

            //     $query->where('department_id', $deptId)
            //         ->whereHas('user', function ($q) use ($user) {
            //             $q->where('organization_id', $user->organization_id);
            //         });
            // }

            $staffs = $query->get();
            //print_r($staffs); exit;
            return view('systemsetting::staffs.index', compact('staffs'));

        } catch (Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back();
        }

    }

    public function lindex(Request $request)
    {
        $courseId = $request->get('users', '');
        $start = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : '';
        $end = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : '';

        try {
        
            $students = null;
            $enrolls = [];
            $courses = [];

        //    print_r($students); exit;
            return view('systemsetting::staffs.lindex', compact('courseId', 'start', 'end', 'enrolls', 'courses', 'students'));

        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed', $e), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function getUsersData(Request $request)
    {
        $user = Auth::user();

        $query = User::select('id','name','image','email','organization_id','manager_email','created_at');

        if ($user->role_id == 11) {
            $query->where('organization_id', $user->organization_id);
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

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($query) {
                return view('systemsetting::staffs.learner._td_learner_checkbox', compact('query'));
            })
            ->addColumn('image', function ($query) {
                return view('backend.partials._small_profile_image', [
                    'image' => $query->image ?? null,
                    'user'  => $query ?? null
                ])->render();
            })
            ->addColumn('action', function ($query) {

                return view('systemsetting::staffs.learner._td_learner_log', compact('query'));

            })->rawColumns(['image', 'action'])->make(true);
    }

    public function resetUserRoles(Request $request)
    {
        $request->validate([
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',

            'role_ids'   => 'required|array|min:1',
            'role_ids.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();

        try {

            foreach ($request->user_ids as $userId) {

                foreach ($request->role_ids as $roleId) {

                    // Your existing role mapping
                    if ($roleId == 14) {
                        $roleId = 3;
                    }

                    // Check whether user already has this role
                    $userRole = UserRoles::where('user_id', $userId)
                        ->where('role_id', $roleId)
                        ->first();

                    if ($userRole) {

                        // Already exists -> update
                        $userRole->updated_at = now();
                        $userRole->save();

                    } else {

                        // Doesn't exist -> insert
                        UserRoles::create([
                            'user_id' => $userId,
                            'role_id' => $roleId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Roles updated successfully'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StaffRequest $request)
    {
        DB::beginTransaction();
        try {
            if (empty($request->username)) {
                $phone = null;
            } else {
                $phone = $request->username;
            }
            if ($request->password) {
                try {
                    
                    $data = $request->except("_token");
                  //  print_r($data); exit;
                    $user = new User();
                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->phone = $phone;
                    $user->username = $data['email'];
                    $user->gender = $data['gender'] ?? 'male';
                    $user->role_id = $data['role_id'] ?? 3;
                    $user->country = 102 ?? null;
                    $user->organization_id = Auth::user()->organization_id ?? null;
                    $user->tenant_id = Auth::user()->tenant_id ?? null;
                    $user->password = Hash::make($data['password']);
                    $user->email_verified_at = now();
                    $map = [
                        4 => 3,
                        9 => 4,
                        2 => 1,
                    ];

                    if (isset($map[$data['role_id']])) {
                        $user->user_type = $map[$data['role_id']];
                    }

                    $user->save();

                    if ($request->image) {
                        $user->image = $this->generateLink($request->image, $user->id, get_class($user), 'image');
                    }
                    $user->save();
                    // print_r($user); exit;
                    if ($user) {
                        // $user, $type, $data, $shortcodes = []
                        $type = null;
                        $slug1 = session('tenant_slug');
                        $data1 = [
                            'email' => $request->email,
                            'password' => $request->password,
                            'login' => route('tenant.login', ['tenant_slug' => $slug1]),
                            'year' => '2026-27',
                            'footer' => 'Warm Welcome'
                        ];
                        send_credential_email($user, $type, $data1, $shortcodes = []);
                    }

                    applyDefaultRoleToUser($user);
                    $staff = new Staff;
                    $staff_position = $request->user_type;
                    if ($staff_position == 1) {
                        $staff->is_head = 1;
                    } elseif ($staff_position == 2) {
                        $staff->is_class_teacher = 1;
                    }
                    $staff->employee_id = 'EMP-' . $user->id;
                    $staff->user_id = $user->id;
                    $staff->department_id = $data['department_id'];
                    $staff->phone = $user->phone;
                    $staff->qualification = $data['qualification_info'] ?? null;
                    $staff->subject = $data['subject'] ?? null;
                    $staff->current_address = $data['current_address'] ?? null;
                    $staff->permanent_address = $data['permanent_address'] ?? null;
                    if ($request->role_id == 9) {
                        $staff->handle_year = $data['handle_year'];
                    } else {
                        $staff->handle_year = null;
                    }
                    
                    $staff->save();

                    DB::commit();
                    Toastr::success(trans('common.Operation successful'), trans('common.Success'));
                    return redirect()->route('staffs.index');
                } catch (Exception $e) {
                    DB::rollBack();
                    Toastr::error($e->getMessage() . $e->getLine() . $e->getFile());
                    return redirect()->back();
                }
            } else {
                DB::rollBack();
                Toastr::error(__('common.Something Went Wrong'));
                return redirect()->back();
            }
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error(__('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function assignRoles(Request $request)
    {
         $request->validate([
            'ids' => 'required',
            'role_id' => 'required'
        ]); 
        
        // print_r($request->all()); exit;
        if (isset($request->approve)) {
             $ids = explode(',', $request->ids);
            // print_r($ids); exit;
            foreach ($ids as $id) {

                if (demoCheckById($id, [1,2,3,4,5,6,7,8,9,10])) {
                    return redirect()->back();
                }
                
                $enroll = User::updateOrCreate(
                    [
                        'id'   => $id
                    ],
                    [
                        'role_id'    => $request->role_id ?? null,
                        'updated_at' => now(),
                    ]
                );

            }

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        }
        
    }

    public function show(Request $request)
    {
        try {
            $staffDetails = $this->userRepository->find($request->id);
            if (isModuleActive('HumanResource')) {
                $leaveDetails = $this->leaveRepository->user_leave_history($staffDetails->user_id);
                $total_leave = $this->leaveRepository->total_leave($staffDetails->user_id);
                $apply_leave_histories = $this->leaveRepository->user_leave_history($staffDetails->user_id);
            } else {
                $leaveDetails = null;
                $total_leave = null;
                $apply_leave_histories = null;
            }

            //            $payrollDetails = $this->payrollRepository->userPayrollDetails($request->id);
            //            $loans = $this->applyLoanRepository->staffLoans($staffDetails->user->id);
            $staffDocuments = $this->userRepository->findDocument($request->id);
            $payrollDetails = collect();
            $loans = collect();
            return view('systemsetting::staffs.viewStaff', [
                "staffDetails" => $staffDetails,
                "leaveDetails" => $leaveDetails,
                "total_leave" => $total_leave,
                "staffDocuments" => $staffDocuments,
                "payrollDetails" => $payrollDetails,
                'apply_leave_histories' => $apply_leave_histories,
                "loans" => $loans
            ]);
        } catch (Exception $e) {
            Toastr::error(trans('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function report_print(Request $request)
    {
        try {
            $staffDetails = $this->userRepository->find($request->id);
            return view('systemsetting::staffs.print_view', [
                "staffDetails" => $staffDetails,
            ]);
        } catch (Exception $e) {
            Toastr::error(trans('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function edit(Request $request,$slug, $id)
    {
        try {
           // dd($id);
            $staff = $this->userRepository->find($id);
            //print_r($staff); exit;
            $roles = Role::where('type', '!=', 'normal_user')->get()->except(1);
            return view('systemsetting::staffs.edit', [
                "staff" => $staff,
                "roles" => $roles,
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function destroy($id)
    {

        if (demoCheckById($id,[44,45,46,47,48])) {
            return redirect()->back();
        }
        try {
            $staff = $this->userRepository->delete($id);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error(__('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function status_update(Request $request)
    {
        try {
            // print_r($id); exit;
            $staff = $this->userRepository->statusUpdate($request->except("_token"));
            return response()->json([
                'success' => trans('common.Operation successful')
            ]);
        } catch (Exception $e) {

            return response()->json([
                'error' => trans('common.Something Went Wrong')
            ]);
        }
    }

    public function document_store(Request $request)
    {
        $validation_rules = [
            'name' => 'required',
            'file' => 'required',
        ];
        $request->validate($validation_rules, validationMessage($validation_rules));

        try {
            if ($request->file('file') != "" && $request->name != "") {
                $file = $request->file('file');
                $extension = strtolower($file->getClientOriginalExtension());
                 if (in_array($extension,['pdf','doc','docx'])) {
                    $document = 'staff-' . md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

                    if (!File::isDirectory('uploads/staff/document/')) {
                        File::makeDirectory('uploads/staff/document/', 0777, true, true);
                    }

                    $file->move('uploads/staff/document/', $document);
                    $document = 'uploads/staff/document/' . $document;
                    $staffDocument = new StaffDocument();
                    $staffDocument->name = $request->name;
                    $staffDocument->staff_id = $request->staff_id;
                    $staffDocument->documents = $document;
                    $staffDocument->save();
                    Toastr::success(trans('common.Operation successful'), trans('common.Success'));

                }else{
                    Toastr::error(trans('validation.document_file.mimes'), trans('common.Failed'));

                }
            }
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error(__('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function document_destroy($id)
    {
        try {
            $staff = $this->userRepository->deleteStaffDoc($id);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            LogActivity::errorLog($e->getMessage() . ' - detected for Staff Document Destroy');
            Toastr::error(__('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function profile_view()
    {
        try {
            $staffDetails = $this->userRepository->find(Auth::user()->staff->id);
            if (isModuleActive('HumanResource')) {
                $leaveDetails = $this->leaveRepository->user_leave_history(Auth::id());
                $total_leave = $this->leaveRepository->total_leave(Auth::id());
                $apply_leave_histories = $this->leaveRepository->user_leave_history(Auth::id());
            } else {
                $leaveDetails = null;
                $total_leave = null;
                $apply_leave_histories = null;
            }

            $payrollDetails = $this->payrollRepository->userPayrollDetails(Auth::user()->staff->id);
            $staffDocuments = $this->userRepository->findDocument(Auth::user()->staff->id);
            $loans = $this->applyLoanRepository->staffLoans(Auth::id());
            return view('backEnd.profiles.profile', [
                "staffDetails" => $staffDetails,
                "leaveDetails" => $leaveDetails,
                "total_leave" => $total_leave,
                "staffDocuments" => $staffDocuments,
                "payrollDetails" => $payrollDetails,
                'apply_leave_histories' => $apply_leave_histories,
                "loans" => $loans
            ]);
        } catch (Exception $e) {
            return redirect()->back();
        }
    }

    public function profile_edit(Request $request)
    {
        try {
            $user = $this->userRepository->findUser($request->id);
            return view('backEnd.profiles.editProfile', [
                "user" => $user
            ]);
        } catch (Exception $e) {
            return redirect()->back();
        }
    }

    public function profile_update(Request $request, $id)
    {
        /*if (env('APP_SYNC')) {
            Toastr::error('Restricted in demo mode');
             return redirect()->back();
        }*/

        $validation_rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . Auth::id(),
            'phone' => 'sometimes|nullable|unique:staffs,phone,' . Auth::user()->staff->id,
            'password' => 'sometimes|nullable|confirmed',
            'password_confirmation' => 'required_with:password'
        ];
        $request->validate($validation_rules, validationMessage($validation_rules));
        if (Auth::user()->role_id != 1) {
            $$validation_rules = [
                'bank_name' => 'required',
                'bank_branch_name' => 'required',
                'bank_account_name' => 'required',
                'bank_account_no' => 'required',
                'current_address' => 'required',
                'permanent_address' => 'required',
            ];
            $request->validate($validation_rules, validationMessage($validation_rules));
        }
        try {
            $this->userRepository->updateProfile($request->except("_token"), $id);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            Toastr::success(__('common.Staff info has been updated Successfully'));
            return redirect()->back();

        } catch (Exception $e) {
            Toastr::error(__('common.Something Went Wrong'));
            return redirect()->back();
        }
    }

    public function csv_upload()
    {
        return view('systemsetting::staffs.upload_via_csv.create');
    }

    public function csv_upload_staff_store1(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:2048'
        ]);
        ini_set('max_execution_time', 0);
        DB::beginTransaction();
        try {
            $this->userRepository->csv_upload_staff($request->except("_token"));
            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            if ($e->getCode() == 23000) {
                Toastr::error(trans('frontend.Duplicate entry is exist in your file'));
            } else {
                Toastr::error(__('common.Something Went Wrong'));
            }
            return redirect()->back();
        }

    }

    public function csv_upload_staff_store(Request $request)
    {
        if (saasPlanCheck('staff')) {
            Toastr::error(trans('frontend.You have reached staff limit'), trans('common.Failed'));
            return redirect()->back();
        }
        if (demoCheck()) {
            return redirect()->back();
        }

        $rules = [
            'file' => 'required',
        ];
        $this->validate($request, $rules, validationMessage($rules));


        $extensions = ["xls", "xlsx"];
        $result = strtolower($request->file->getClientOriginalExtension());
        if (!in_array($result, $extensions)) {
            Toastr::warning(trans('frontend.The file must be a file of type: xlsx, csv or xls'),);
            return redirect()->back();
        }
        $path = $request->file('file');
        $import = new ImportStaff();

        $duplicateEmails = $import->duplicateEmails;
        $duplicatePhones = $import->duplicatePhones;
        $importedEmails = $import->importedEmails;
        $failedRows = $import->failedRows;

        if (!empty($duplicateEmails)) {

            $emailList = collect($duplicateEmails)
                ->pluck('email')
                ->implode(', ');

            Toastr::warning(
                'Duplicate emails skipped: ' . $emailList,
                'Duplicate'
            );
        }

        if (!empty($duplicatePhones)) {

            $phoneList = collect($duplicatePhones)
                ->pluck('phone')
                ->implode(', ');

            Toastr::warning(
                'Duplicate phone numbers skipped: ' . $phoneList,
                'Duplicate'
            );
        }

        Excel::import(
            $import,
            $path,
            'local',
            \Maatwebsite\Excel\Excel::XLSX
        );



        try {
            AuditLog::create([
                'log_name' => 'regular_student_import',
                'description' => 'Regular student import executed by user ' . Auth::id(),
                'subject_id' => Auth::id(),
                'subject_type' => 'User',
                'causer_id' => Auth::id(),
                'causer_type' => 'User',
                'properties' => json_encode(['imported_at' => now()->toDateTimeString()]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('AuditLog creation failed: ' . $e->getMessage());
        }

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }


    public function csv_upload_staff_validate(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {

            $sheets = Excel::toCollection(null, $request->file('file'));

            $rows = $sheets->first();

            if (!$rows || $rows->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The uploaded file is empty.',
                ], 422);
            }

            // First row contains Excel headings
            $headers = $rows->first()
                ->map(function ($header) {
                    return strtolower(trim($header));
                })
                ->toArray();

            // Remove heading row
            $dataRows = $rows->skip(1);

            $duplicateEmails = [];
            $duplicatePhones = [];

            $excelEmails = [];
            $excelPhones = [];

            foreach ($dataRows as $index => $row) {

                // Convert numeric row into associative array
                $row = collect($row)->toArray();

                $rowData = [];

                foreach ($headers as $key => $header) {
                    $rowData[$header] = $row[$key] ?? null;
                }

                $email = strtolower(
                    trim($rowData['email'] ?? '')
                );

                $phone = trim(
                    (string) ($rowData['phone'] ?? '')
                );

                $excelRowNumber = $index + 2;


                /*
                |--------------------------------------------------------------------------
                | EMAIL VALIDATION
                |--------------------------------------------------------------------------
                */

                if (!empty($email)) {

                    // Duplicate inside uploaded Excel
                    if (in_array($email, $excelEmails)) {

                        $duplicateEmails[] = [
                            'value' => $email,
                            'reason' => 'Duplicate email in uploaded file',
                            'row' => $excelRowNumber,
                        ];

                    }
                    // Email already exists in database
                    elseif (
                        User::where('email', $email)->exists()
                    ) {

                        $duplicateEmails[] = [
                            'value' => $email,
                            'reason' => 'Email already exists',
                            'row' => $excelRowNumber,
                        ];
                    }

                    $excelEmails[] = $email;
                }


                /*
                |--------------------------------------------------------------------------
                | PHONE VALIDATION
                |--------------------------------------------------------------------------
                */

                if (!empty($phone)) {

                    // Duplicate inside uploaded Excel
                    if (in_array($phone, $excelPhones)) {

                        $duplicatePhones[] = [
                            'value' => $phone,
                            'reason' => 'Duplicate phone in uploaded file',
                            'row' => $excelRowNumber,
                        ];

                    }
                    // Phone already exists in database
                    elseif (
                        User::where('phone', $phone)->exists()
                    ) {

                        $duplicatePhones[] = [
                            'value' => $phone,
                            'reason' => 'Phone already exists',
                            'row' => $excelRowNumber,
                        ];
                    }

                    $excelPhones[] = $phone;
                }
            }


            return response()->json([
                'status' => true,
                'has_duplicates' =>
                    !empty($duplicateEmails) ||
                    !empty($duplicatePhones),

                'duplicate_emails' => $duplicateEmails,
                'duplicate_phones' => $duplicatePhones,
            ]);

        } catch (\Exception $e) {

            Log::error(
                'STAFF AJAX VALIDATION ERROR: ' .
                $e->getMessage()
            );

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function csv_upload_learner_store(Request $request)
    {
        if (saasPlanCheck('student')) {
            Toastr::error(trans('frontend.You have reached student limit'), trans('common.Failed'));
            return redirect()->back();
        }
        if (demoCheck()) {
            return redirect()->back();
        }

        $rules = [
            'file' => 'required',
        ];
        $this->validate($request, $rules, validationMessage($rules));


        $extensions = ["xls", "xlsx"];
        $result = strtolower($request->file->getClientOriginalExtension());
        if (!in_array($result, $extensions)) {
            Toastr::warning(trans('frontend.The file must be a file of type: xlsx, csv or xls'),);
            return redirect()->back();
        }
        $path = $request->file('file');
        Excel::import(new ImportRegularStaff(), $path, 'local', \Maatwebsite\Excel\Excel::XLSX);

        try {
            AuditLog::create([
                'log_name' => 'regular_student_import',
                'description' => 'Regular student import executed by user ' . Auth::id(),
                'subject_id' => Auth::id(),
                'subject_type' => 'User',
                'causer_id' => Auth::id(),
                'causer_type' => 'User',
                'properties' => json_encode(['imported_at' => now()->toDateTimeString()]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            \Log::error('AuditLog creation failed: ' . $e->getMessage());
        }

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();

    }



    public function active($id)
    {
        try {
            User::where('id', $id)->update(['is_active' => 1, 'inactive_date' => NULL, 'inactive_reason' => NULL]);
            return response()->json(['status' => 200]);
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();
        }

    }

    public function update(StaffUpdateRequest $request, $slug=null, $id)
    {
        DB::beginTransaction();
        try {
           // print_r($id); exit;
            $slug = $request->slug ?? null;
            $staff = $this->updateUser($request->except("_token"), $id);
            $created_by = Auth::user()->name;
            $company = Settings('company_name');
            $content = 'Your info has been updated as a Staff by ' . $created_by . ' for ' . $company . ' ';
            $number = $staff->phone ?? '';
            $message = 'Your info Have Been updated by ' . $created_by . ' as a Staff for ' . $company . ' ';
            //            $this->sendNotification($staff, $staff->user->email, 'Staff Added', $content, $number, $message);
            DB::commit();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->route('staffs.index');
        } catch (Exception $exception) {
            DB::rollBack();
            GettingError($exception->getMessage(), url()->current(), request()->ip(), request()->userAgent());

            return redirect()->back();
        }
    }

    public function updateUser(array $data, $id)
    {
        //print_r($id); exit;
        $user = User::findOrFail($id);
       // print_r($user); exit;
        $user->image = null;
        $user->save();


        $this->removeLink($user->id, get_class($user));
        if (isset($data['image'])) {
            $user->image = $this->generateLink($data['image'], $user->id, get_class($user), 'image');
        }

        //print_r($data); exit;

        $user->name = $data['name'];
        $user->phone = $data['phone'];
        $user->email = $data['email'];
        $user->gender = $data['gender'] ?? 'male';
        $user->username = $data['username'] ?? null;
        
        // if ($data['password']) {
        //     $user->password = Hash::make($data['password']);
        // }

        if ($user->save()) {
            $staff = $user->staff;
            $staff->department_id = $data['department_id'];
            $staff->phone = $user->phone;
            $staff->qualification = $data['qualification_info'] ?? null;
            $staff->subject = $data['subject'] ?? null;
            $staff->handle_year = $data['handle_year'] ?? null;
            $staff->date_of_joining = isset($data['date_of_joining']) ? Carbon::parse($data['date_of_joining'])->format('Y-m-d') : date('Y-m-d');
            if (!empty($data['provisional_months'])) {
                $staff->provisional_months = $data['provisional_months'];
            }
            if (is_null($data['date_of_birth'])) {
                $data['date_of_birth'] = now();
            }
            $staff->date_of_birth = Carbon::parse($data['date_of_birth'])->format('Y-m-d');
            
            $staff->current_address = $data['current_address'] ?? null;
            $staff->permanent_address = $data['permanent_address'] ?? null;
            $staff->save();
            return $user;
        }
    }

    public function inactive($id)
    {
        try {
            $user = User::find($id);
            return view('systemsetting::staffs.components._inactive_modal', ['user' => $user]);
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();
        }

    }

    public function inactiveUpdate($id, Request $request)
    {
        try {
            User::where('id', $id)->update([
                'is_active' => 0,
                'inactive_date' => date('Y-m-d', strtotime($request->inactive_date)),
                'inactive_reason' => $request->reason,
            ]);
            return response()->json(['status' => 200]);
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();

        }
    }

    public function documentUpload()
    {
        try {
            $data['documents'] = StaffDocument::where('staff_id', Auth::id())->get();
            return view('systemsetting::staffs.components._document', $data);
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();

        }
    }

    public function documentUploadStore(Request $request)
    {
        try {
            $validation_rules = [
                'documents.*.name' => 'nullable',
                'documents.*.file' => 'nullable|mimes:pdf,xlx,csv,jpg,jpeg,png,zip,xlsx',
            ];
            $request->validate($validation_rules, validationMessage($validation_rules));
            $upload_path = 'public/uploads/staff_document';
            if (isset($request->existing_document_ids)) {
                foreach ($request->existing_document_ids as $eid) {
                    $row = StaffDocument::find($eid);
                    if (isset($request->file[$eid]) && $row->documents) {
                        $file_url = $this->fileUploadAndUpdate($request->file[$eid], $upload_path, $row->documents);
                    } elseif (isset($request->file[$eid]) && !$row->documents) {
                        $file_url = $this->fileUpload($request->file[$eid], $upload_path);
                    } else {
                        $file_url = $row->documents;
                    }
                    StaffDocument::where('id', $eid)->update([
                        'name' => $request->name[$eid],
                        'documents' => $file_url,
                    ]);
                }
            }
            $documents = $request->documents;
            foreach ($documents as $document) {
                if (isset($document['name']) && isset($document['file'])) {
                    StaffDocument::create([
                        'staff_id' => Auth::id(),
                        'name' => $document['name'],
                        'documents' => $this->fileUpload($document['file'], $upload_path),
                    ]);
                }
            }
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();

        }
    }

    public function create()
    {
        $roles = Role::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('systemsetting::staffs.create', compact('roles'));

    }

    public function documentRemove($id)
    {
        try {
            $document = StaffDocument::find($id);
            $this->deleteImage($document->documents);
            $document->delete();
            return response()->json(['status' => 200]);
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();

        }
    }

    public function staffResume($id = null)
    {
        try {
            if ($id) {
                $data['user'] = User::where('id', $id)->with('role')->first();
                return view('systemsetting::staffs.components._resume_modal', $data);
            } else {
                $data['user'] = User::where('id', Auth::id())->with('role')->first();
                return view('systemsetting::staffs.components._resume', $data);
            }
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), trans('common.Failed'));
            return back();

        }
    }

    public function settings()
    {
        return view('systemsetting::staffs.settings');
    }

    public function settingsPost(Request $request)
    {
        UpdateGeneralSetting('staff_can_view_course', $request->staff_can_view_course);
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function resetRoles(Request $request)
    {
        $user = Auth::user();

        DB::transaction(function () use ($user) {

            // Step 1: Get all employee_ids who are assigned as reporting managers
            $managerEmployeeIds = User::where('organization_id', $user->organization_id)
                ->whereNotNull('reporting_manager_id')
                ->pluck('reporting_manager_id')
                ->unique()
                ->toArray();

            // Step 2: Set all users as Employee role (3) first
            User::where('organization_id', $user->organization_id)
                ->where('reset_role', 0)
                ->whereNotIn('role_id', [11]) // skip super admin
                ->update([
                    'role_id' => 3,
                    'reset_role' => 1
                ]);

            // Step 3: Update Managers (role_id = 13)
            User::where('organization_id', $user->organization_id)
                ->whereIn('employee_id', $managerEmployeeIds)
                ->update([
                    'role_id' => 13
                ]);
        });

        return back()->with('success', 'Roles updated successfully.');
    }

    public function moveToBench(Request $request)
    {
        $learner = User::find($request->id);

        if (!$learner) {
            return response()->json([
                'message' => 'Learner not found'
            ], 404);
        }

        $learner->status = 2;
        $learner->save();

        return response()->json([
            'status'  => true,
            'message' => trans('common.Learner moved to bench successfully')
        ]);
    }
}
