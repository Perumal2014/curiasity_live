<?php



namespace Modules\Organization\Http\Controllers;



use App\Http\Controllers\Controller;

use App\Traits\UploadMedia;

use App\User;

use App\Models\Tenants;

use Brian2694\Toastr\Facades\Toastr;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Storage;

use Modules\CourseSetting\Entities\Course;

use Modules\RolePermission\Entities\RolePermission;

use Yajra\DataTables\Facades\DataTables;

use Modules\Setting\Entities\TenantType;

use Illuminate\Support\Str;

use Modules\RolePermission\Entities\Role;

use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Hash;

use App\Models\TenantPlans;



class OrganizationController extends Controller

{

    use UploadMedia;



    public function index()

    {

        try {

            //print_r('test'); exit;

            return view('organization::organization.index');

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));

            return redirect()->back();

        }

    }



    public function TenantIndex()

    {

        try {

            $tenants = Tenants::all();

            // print_r($tenants); exit;

            return view('organization::organization.tenantindex', compact('tenants'));

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));

            return redirect()->back();

        }

    }



    public function create()

    {

        try {

            $tenantTypes = TenantType::all();

            $usedTenantIds = User::whereNotNull('organization_id')

                            ->pluck('organization_id');

            return view('organization::organization.create', compact('tenantTypes'));

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed', $e->getMessage()));

            return redirect()->back();

        }

    }



    public function TenantCreate()

    {

        try {



            $tenantTypes = TenantType::all();



            return view('organization::organization.tenant_create', compact('tenantTypes'));

        } catch (\Exception $e) {   

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));

            return redirect()->back();

        }

    }



    public function edit1($slug = null, $id)

    {

        try {

           

            $data['tenantTypes'] = TenantType::all();

            $data['tenant'] = Tenants::where('id', $id)->first();

            

            

            // print_r($data['user']); exit;    

            return view('organization::organization.create', $data);

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));

            return redirect()->back();

        }

    }

   public function edit($slug = null, $id)

    {

        try {

           

            $data['tenantTypes'] = TenantType::all();

            $data['tenant'] = Tenants::where('id', $id)->first();

            $data['plans'] = TenantPlans::all();

            

            

            // print_r($data['user']); exit;    

            return view('organization::organization.create', $data);

        } catch (\Exception $e) {

            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));

            return redirect()->back();

        }

    }





    public function store(Request $request)
    {

        if (saasPlanCheck('instructor')) {

            Toastr::error(trans('frontend.You have reached instructor limit'), trans('common.Failed'));

            return redirect()->route('organization.index');

        }

        Session::flash('type', 'store');



        if (demoCheck()) {

            return redirect()->back();

        }



        $rules = [

            'tenant_id' => 'required',

            'name' => 'required',

            'slug' => 'required|nullable|string|unique:users,slug',

            'phone' => 'required|nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|unique:users,phone',

            'email' => 'required|email|unique:users,email',

            'password' => 'required|min:8|confirmed',

            'image'   => 'required'

        ];





        $this->validate($request, $rules, validationMessage($rules));





        try {

           

            // print_r($request->all()); exit;

            $tenant = new Tenants;

            $tenant->tenant_name = $request->name ?? null;

            $tenant->tenant_email = $request->email ?? null;

            $tenant->tenant_type = $request->tenant_id ?? null;

            if (empty($request->phone)) {

                $tenant->phone = null;

            } else {

                $tenant->phone = $request->phone;

            }

            if (isModuleActive('LmsSaas')) {

                $tenant->lms_id = app('institute')->id;

            } else {

                $tenant->lms_id = 1;

            }

            $tenant->added_by = Auth::id();

            $tenant->tenant_slug = $request->slug ? Str::slug($request->slug) : null;

            $tenant->created_by = Auth::id();

            

            $tenant->save();

            if ($request->file('image') != "") {

                $file = $request->file('image');

                $tenant->tenant_logo = $this->saveImage($file);

            }

            $tenant->save();

            if ($request->image) {

                $tenant->tenant_logo = $this->generateLink($request->image, $tenant->id, get_class($tenant), 'image');

            }





            $tenant->save();

            applyDefaultRoleToUser($tenant);

            assignStaffToUser($tenant);

            //print_r($tenant); exit;

            $user = new User;

            $user->slug = $request->slug ?? null;

            $user->name = $request->name ?? null;

            $user->email = $request->email ?? null;

            $user->username = null;

            $user->password = bcrypt($request->password);

            $user->about = $request->about ?? null;

            $user->dob = getPhpDateFormat($request->dob);



            if (empty($request->phone)) {

                $user->phone = null;

            } else {

                $user->phone = $request->phone;

            }

            $user->language_id = Settings('language_id');

            $user->language_code = Settings('language_code');

            $user->language_name = Settings('language_name');

            $user->language_rtl = Settings('language_rtl');

            $user->country = Settings('country_id');

            $user->facebook = $request->facebook;

            $user->twitter = $request->twitter;

            $user->linkedin = $request->linkedin;

            $user->instagram = $request->instagram;

            $user->added_by = Auth::id();

            $user->tenant_id = $request->tenant_id ?? null;

            $user->organization_id = $tenant->id ?? null;

            $user->role_id = getRoleIdByTenantType($request->tenant_id);





            $user->email_verify = 1;

            $user->email_verified_at = now();

            if (isModuleActive('LmsSaas')) {

                $user->lms_id = app('institute')->id;

            } else {

                $user->lms_id = 1;

            }

            if ($request->file('image') != "") {

                $file = $request->file('image');

                $user->image = $this->saveImage($file);

            }

            $user->user_type = 1;

            $user->referral = generateUniqueId();

            $user->special_commission = null;

            $user->save();

            if ($request->image) {

                $user->image = $this->generateLink($request->image, $user->id, get_class($user), 'image');

            }

            $user->save();

            if ($user) {

                $data = array(

                'email' => $request->email,

                'password' => $request->password,

                'login' => route('tenant.login', [session('tenant_slug')]),

                'year' => '2026-27',

                'footer' => 'Warm Welcome'

            );

            $notify = send_credential_email(Auth::user(), 'type', $data, 'shortcodes');

            

            

            }

            applyDefaultRoleToUser($user);

            assignStaffToUser($user);



            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

            return redirect()->route('organization.index');

        } catch (\Exception $e) {

            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }

    }



    





    public function update(Request $request)

    {

        Session::flash('type', 'update');



        if (saasPlanCheck('instructor')) {

            Toastr::error(trans('frontend.You have reached instructor limit'), trans('common.Failed'));

            return redirect()->route('organization.index');

        }



        if (demoCheck()) {

            return redirect()->back();

        }

        

        $tenantupdate = Tenants::where('id', $request->id_value)->first();

           

    //    print_r($tenantupdate); exit;

        $rules = [

            'tenant_id' => 'required',

            'name'      => 'required|string|max:255',

            'slug'  => 'required',

            'phone' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:5',

            'email' => 'required|email',

        ];



        $this->validate($request, $rules, validationMessage($rules));

        try {

            

           

            // print_r($tenantupdate); exit;                // Update fields

            $tenantupdate->tenant_name  = $request->name ?? null;

            $tenantupdate->tenant_email = $request->email ?? null;

            $tenantupdate->tenant_type  = $request->tenant_id ?? null;

            $tenantupdate->phone        = $request->phone ?? null;



            $tenantupdate->lms_id = isModuleActive('LmsSaas') 

                ? app('institute')->id 

                : 1;



            $tenantupdate->tenant_slug = $request->slug 

                ? Str::slug($request->slug) 

                : null;



           // print_r($tenantupdate); exit;



            $tenantupdate->save();



            

           

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

            return redirect()->route('organization.index');



        } catch (\Exception $e) {

            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }

    }



    public function tenantupdate(Request $request)

    {

        Session::flash('type', 'update');



        if (demoCheck()) {

            return redirect()->back();

        }

        $rules = [

            'name' => 'required',

            'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:1|unique:tenant_list,phone,' . $request->id,

            'email' => 'required|email|unique:tenant_list,tenant_email,' . $request->id,

        ];



        $this->validate($request, $rules, validationMessage($rules));



        $tenant = Tenants::findOrFail($request->id);



        try {

            $tenant->tenant_name = $request->name;

            $tenant->tenant_email = $request->email;

            if (empty($request->phone)) {

                $tenant->phone = null;

            } else {

                $tenant->phone = $request->phone;

            }



            $tenant->tenant_slug = $request->slug ?? null;

            $tenant->tenant_type = $request->tenant_id ?? null;

            $tenant->tenant_plan_id = $request->plan_id ?? null;

            $tenant->verified_status = $request->verified_status ?? null;



           

            $tenant->save();



            // Update image only if a new one is selected

            if ($request->filled('image') || $request->image) {



                // Remove old image

                if ($tenant->tenant_logo) {

                    $this->removeLink($tenant->id, get_class($tenant));

                }



                // Upload new image

                $tenant->tenant_logo = $this->generateLink(

                    $request->image,

                    $tenant->id,

                    get_class($tenant),

                    'image'

                );



                $tenant->save();

            }



            if ($request->filled('banner') || $request->banner) {



                if ($tenant->tenant_banner) {

                    $this->removeLink($tenant->id, get_class($tenant), 'banner');

                }



                $tenant->tenant_banner = $this->generateLink(

                    $request->banner,

                    $tenant->id,

                    get_class($tenant),

                    'banner'

                );



                $tenant->save();

            }



            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

            return redirect()->route('organization.tenantindex');



        } catch (\Exception $e) {

            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }

    }

    



    public function tenantStore(Request $request)

    {

        if (saasPlanCheck('instructor')) {

            Toastr::error(trans('frontend.You have reached instructor limit'), trans('common.Failed'));

            return redirect()->route('organization.index');

        }

        Session::flash('type', 'store');



        if (demoCheck()) {

            return redirect()->back();

        }





        $rules = [

            'name' => 'required',

            'slug' => 'required|nullable|string|unique:tenant_list,tenant_slug',

            'phone' => 'required|nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|unique:tenant_list,phone',

            'email' => 'required|email|unique:tenant_list,tenant_email',

        ];





        $this->validate($request, $rules, validationMessage($rules));





        try {



            // print_r($request->all()); exit;



            $tenant = new Tenants;

            $tenant->tenant_name = $request->name ?? null;

            $tenant->tenant_email = $request->email ?? null;

            if (empty($request->phone)) {

                $tenant->phone = null;

            } else {

                $tenant->phone = $request->phone;

            }

            if (isModuleActive('LmsSaas')) {

                $tenant->lms_id = app('institute')->id;

            } else {

                $tenant->lms_id = 1;

            }

            $tenant->added_by = Auth::id();

            $tenant->tenant_slug = $request->slug ? Str::slug($request->slug) : null;

            $tenant->created_by = Auth::id();

            $tenant->tenant_type = $request->tenant_id ?? null;

            

            

            if ($request->file('image') != "") {

                $file = $request->file('image');

                $tenant->tenant_logo = $this->saveImage($file);

            }

            

            $tenant->save();



            if ($tenant) {



                    $password = generateUniqueId(8);

                    

                    

                    $user = new User;

                    $user->slug = $request->slug ?? null;

                    $user->name = $request->name ?? null;

                    $user->email = $request->email ?? null;

                    $user->username = $request->email ?? null;

                    $user->password = Hash::make($password);

                    

                    if ($request->tenant_id == 3) {

                        $user->role_id = 8;

                    } else {

                        $user->role_id = getRoleIdByTenantType($request->tenant_id);

                    }

                   

                    $user->organization_id = $tenant->id ?? null;

                    $user->email_verify = 1;

                    $user->email_verified_at = now();

                    $user->lms_id = isModuleActive('LmsSaas') ? app('institute')->id : 1;

                    // print_r($user); exit;

                    if ($request->file('image') != "") {

                        $file = $request->file('image');

                        $user->image = $this->saveImage($file);

                    }

                    $user->is_hr = 0;

                    $user->plan_assigned = 1;

                    $user->tenant_id = $request->tenant_id ?? null;

                    // print_r($user); exit;    

                    $user->save();

                    

                    $data = array(

                        'email' => $request->email ?? 'perumalmca2014@gmail.com',

                        'password' => $password,

                        'login' => route('tenant.login', $request->slug),

                        'year' => '2026-27',

                        'footer' => 'Warm Welcome'

                    );



                    $notify = send_credential_email(Auth::user(), 'type', $data, 'shortcodes');

            }

            if ($request->image) {

                $tenant->tenant_logo = $this->generateLink($request->image, $tenant->id, get_class($tenant), 'image');

            }

            $tenant->save();



            applyDefaultRoleToUser($tenant);

            assignStaffToUser($tenant);



            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

            return redirect()->route('organization.tenantindex');

        } catch (\Exception $e) {

            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }

    }





    public function destroy(Request $request)

    {

        if (demoCheck()) {

            return redirect()->back();

        }



        $rules = [

            'id' => 'required'

        ];



        $this->validate($request, $rules, validationMessage($rules));



        $user = User::with('courses')->findOrFail($request->id);

        try {

            if (count($user->courses) > 0) {

                Toastr::error($user->name . ' has course. Please remove it first', 'Failed');

                return back();

            }

            $user->delete();

            Toastr::success(trans('common.Operation successful'), trans('common.Success'));

            return redirect()->route('organization.index');



        } catch (\Exception $e) {

            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }

    }





    public function getAllOrganizationData(Request $request)

    {

        $query = Tenants::query()

            ->with('TenantUsers')

            ->withCount([

                // Courses

                'courses as courses_count' => function ($q) {

                    $q->where('type', 1);

                },

                // Quizzes

                'courses as quizzes_count' => function ($q) {

                    $q->where('type', 2);

                },

                // Classes

                'courses as classes_count' => function ($q) {

                    $q->where('type', 3);

                },

            ]);



        // LMS filter

        if (isModuleActive('LmsSaas')) {

            $query->where('lms_id', app('institute')->id);

        } else {

            $query->where('lms_id', 1);

        }



        // Organization role filter

        if (isModuleActive('UserType')) {

            $query->whereHas('userRoles', function ($q) {

                $q->where('role_id', 5);

            });

        } else {

            $query->where('role_id', 5);

        }



        return Datatables::of($query)

            ->addIndexColumn()



            ->editColumn('name', function ($row) {

                return view('backend.my_panel._user_td', ['row' => $row]);

            })



            ->addColumn('students', function ($row) {

                return translatedNumber(

                    optional($row->totalOrganizationUsers)

                        ->where('role_id', 3)

                        ->count()

                );

            })



            ->addColumn('instructors', function ($row) {

                return translatedNumber(

                    optional($row->totalOrganizationUsers)

                        ->where('role_id', 2)

                        ->count()

                );

            })



            ->addColumn('courses', function ($row) {

                return translatedNumber($row->courses_count ?? 0);

            })



            ->addColumn('quizzes', function ($row) {

                return translatedNumber($row->quizzes_count ?? 0);

            })



            ->addColumn('classes', function ($row) {

                return translatedNumber($row->classes_count ?? 0);

            })



            ->editColumn('created_at', function ($row) {

                return showDate($row->created_at);

            })



            ->addColumn('status', function ($row) {

                return view('organization::organization.partials._td_status', compact('row'));

            })



            ->addColumn('action', function ($row) {

                return view('organization::organization.partials._td_action', compact('row'));

            })



            ->rawColumns(['status', 'name', 'action'])

            ->make(true);

    }



    



    public function getAllTenantData(Request $request)

    {

        $with = [];



        $query = Tenants::query("tenantType", "tenantUsers")

                    ->whereHas('tenantUsers', function ($q) {

                    $q->whereNotNull('organization_id');

                        });



        if (isModuleActive('LmsSaas')) {

            $query->where('lms_id', app('institute')->id);

        } else {

            $query->where('lms_id', 1);

        }



        $query->with($with);



//         return response()->json([

//     'sql' => $query->toSql(),

//     'bindings' => $query->getBindings(),

//     'status' => '1234'

// ]);



        return Datatables::of($query)

            ->addIndexColumn()

                ->addColumn('tenant_type', function ($row) {

                    return optional($row->tenantType)->name ?? '-';

                })

                ->addColumn('action', function ($query) {

                    return view('organization::organization.partials._td_action', compact('query'));



                })

            ->make(true);

    }

 public function getAllTenants(Request $request)

    {

        $with = [];



        $query = Tenants::query("tenantType");



        if (isModuleActive('LmsSaas')) {

            $query->where('lms_id', app('institute')->id);

        } else {

            $query->where('lms_id', 1);

        }



        $query->with($with);



                



        return Datatables::of($query)

            ->addIndexColumn()

                ->addColumn('tenant_type', function ($row) {

                    if ($row->tenant_type == 1) {

                        return 'Curiasity';

                    } elseif ($row->tenant_type == 2) {

                        return 'Corporate';

                    } elseif ($row->tenant_type == 3) {

                        return 'Institute';

                    } else {

                        return 'College';

                    }

                    

                })->addColumn('verified', function ($row) {

                    // if ($row->verified_status == 1) {

                    //     return '<span class="badge badge-success">Verified</span>';

                    // } else {

                    //     return '<span class="badge badge-danger">Not Verified</span>';

                    // }

                    if ($row->verified_status == 1) {

                        return 'Verified';

                    } elseif ($row->verified_status == 2) {

                        return 'Pending';

                    } else {

                        return 'Not Verified';

                    }

                })

                ->addColumn('plan', function ($row) {

                    if ($row->tenant_plan_id == 1) {

                        return 'Free';

                    } elseif ($row->tenant_plan_id == 2) {

                        return 'Basic';

                    } elseif ($row->tenant_plan_id == 3) {

                        return 'Standard';

                    } elseif ($row->tenant_plan_id == 4) {

                        return 'Premium';

                    } elseif ($row->tenant_plan_id == 5) {

                        return 'Enterprise';

                    } else {

                        return '-'; 

                    }

                })

                ->addColumn('action', function ($query) {

                    return view('organization::organization.partials._td_action', compact('query'));



                })

            ->make(true);

    }





    public function generateDefaultOrganizationPermission()

    {

        $path = Storage::path('organization.php');

        $organization_permission_route = RolePermission::with(['permission'])->where('role_id', 5)->get();

        $output = [];

        foreach ($organization_permission_route as $permission) {

            $output[] = $permission->permission->route;

        }

        if (file_exists($path)) {

            file_put_contents($path, '');

        }



        file_put_contents($path, '<?php return ' . var_export($output, true) . ';');

    }



    public function getRoleListByOrganization($organization_id)

    {

        //return $organization_id;

        $tenant = Tenants::findOrFail($organization_id);



        $roles = Role::where('tenant_id', $tenant->tenant_type)->get();



        //return $roles;



        return response()->json([

            'status' => 'success',

            'roles'  => $roles,

        ]);



    }

}

