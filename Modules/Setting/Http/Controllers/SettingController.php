<?php

namespace Modules\Setting\Http\Controllers;

use App\Country;
use App\Http\Controllers\Controller;
use App\Traits\UploadMedia;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\FrontendManage\Entities\HomeContent;
use Modules\Org\Entities\OrgBranch;
use Modules\Setting\Entities\InstructorSetup;
use Modules\Setting\Entities\StudentSetup;
use Modules\Setting\Model\BusinessSetting;
use Modules\Setting\Model\Currency;
use Modules\Setting\Model\DateFormat;
use Modules\Setting\Model\GeneralSetting;
use Modules\Setting\Model\TimeZone;
use Modules\Setting\Repositories\GeneralSettingRepositoryInterface;
use Modules\SystemSetting\Entities\EmailSetting;
use App\Models\Feedbacks;
use App\Models\Announcements;
use App\Models\FeedbackReminders;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Models\Tenants;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\TenantGallery;
use App\Models\TenantReviews;
use Modules\Setting\Entities\MediaManager;
use App\Models\FeedbackQuestion;


class SettingController extends Controller
{
    use UploadMedia;

    protected $generalSettingRepository;

    public function __construct(GeneralSettingRepositoryInterface $generalSettingRepository)
    {
        $this->generalSettingRepository = $generalSettingRepository;
    }

    public function activation()
    {
        $business_settings = BusinessSetting::all();
        return view('setting::activation', compact('business_settings'));
    }


    public function general_settings()
    {
        $date_formats = DateFormat::select('normal_view', 'id')->get();
        $languages = getLanguageList();
        $countries = Country::select('id', 'name')->where('active_status', 1)->get();
        $timeZones = TimeZone::select('id', 'time_zone')->get();
        $data = [];
        if (isModuleActive('Org')) {
            $data['branches'] = OrgBranch::orderBy('order', 'asc')->get();
        }
        $logo = GeneralSetting::where('key', 'logo')->first();
        $logo2 = GeneralSetting::where('key', 'logo2')->first();
        $logo3 = GeneralSetting::where('key', 'logo3')->first();
        $favicon = GeneralSetting::where('key', 'favicon')->first();
        $pdfFont = GeneralSetting::where('key', 'datatable_default_font')->first();
        return view('setting::general_settings', $data, compact('timeZones', 'countries', 'languages', 'date_formats', 'logo', 'logo2', 'logo3', 'favicon','pdfFont'));
    }

    public function email_setup()
    {
        $emailSettings = EmailSetting::get();
        $send_mail_setting = $emailSettings->where('email_engine_type', 'php')->first();
        $smtp_mail_setting = $emailSettings->where('email_engine_type', 'smtp')->first();
        $send_grid_mail_setting = $emailSettings->where('email_engine_type', 'sendgrid')->first();

        return view('setting::email_setup2', compact('emailSettings', 'send_mail_setting', 'smtp_mail_setting', 'send_grid_mail_setting'));
    }

    public function seo_setting()
    {
        return view('setting::seo_setting');
    }


    public function index()
    {
        return redirect()->route('home');
    }


    public function update_activation_status(Request $request)
    {
        if (demoCheck()) {
            return 2;
        }

        $business_setting = BusinessSetting::findOrFail($request->id);
        if ($business_setting != null) {
            $business_setting->status = $request->status??0;
            $business_setting->save();
            UpdateGeneralSetting($business_setting->type, $business_setting->status);
            return 1;
        }
        return 0;
    }

    public function maintenance()
    {
        $keys = [
            'maintenance_title',
            'maintenance_sub_title',
            'maintenance_banner',
            'maintenance_status',
        ];
        $setting = HomeContent::whereIn('key', $keys)->get();

        $maintenance_title = $setting->where('key', 'maintenance_title')->first()?->value;
        $maintenance_sub_title = $setting->where('key', 'maintenance_sub_title')->first()?->value;
        $maintenance_banner = $setting->where('key', 'maintenance_banner')->first();
        $maintenance_status = $setting->where('key', 'maintenance_status')->first()?->value;
        return view('setting::maintenance', compact('maintenance_title', 'maintenance_sub_title', 'maintenance_banner', 'maintenance_status'));
    }

    public function maintenanceAction(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $banner = HomeContent::where('key', 'maintenance_banner')->first();
            if ($banner) {
                $url = null;
                $this->removeLink($banner->id, get_class($banner));
                $banner->value = null;
                $banner->save();
                if ($request->maintenance_banner) {
                    $url = $this->generateLink($request->maintenance_banner, $banner->id, get_class($banner), 'value');
                }
                $banner->value = $url;
                $banner->save();
                UpdateHomeContent('maintenance_banner', $url);
            }

            UpdateHomeContent('maintenance_title', $request->maintenance_title);
            UpdateHomeContent('maintenance_sub_title', $request->maintenance_sub_title);
            UpdateHomeContent('maintenance_status', $request->maintenance_status);
            UpdateGeneralSetting('maintenance_status', $request->maintenance_status);


            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error(trans('common.Operation failed'), trans('common.Failed'));
            return redirect()->back();
        }
    }

    public function captcha()
    {
        return view('setting::captcha');
    }

    public function captchaStore(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        $site_key = $request->get('site_key');
        $secret_key = $request->get('secret_key');
        $login_status = $request->get('login_status');
        $reg_status = $request->get('reg_status');
        $contact_status = $request->get('contact_status');
        $is_invisible = $request->get('is_invisible');

        SaasEnvSetting(SaasDomain(), 'NOCAPTCHA_SITEKEY', $site_key);
        SaasEnvSetting(SaasDomain(), 'NOCAPTCHA_SECRET', $secret_key);

        if ($is_invisible == 1) {
            $is_invisible = 'true';
        } else {
            $is_invisible = 'false';
        }
        SaasEnvSetting(SaasDomain(), 'NOCAPTCHA_IS_INVISIBLE', $is_invisible);


        if ($login_status == 1) {
            $login_status = 'true';
        } else {
            $login_status = 'false';
        }
        SaasEnvSetting(SaasDomain(), 'NOCAPTCHA_FOR_LOGIN', $login_status);

        if ($reg_status == 1) {
            $reg_status = 'true';
        } else {
            $reg_status = 'false';
        }
        SaasEnvSetting(SaasDomain(), 'NOCAPTCHA_FOR_REG', $reg_status);

        if ($contact_status == 1) {
            $contact_status = 'true';
        } else {
            $contact_status = 'false';
        }
        SaasEnvSetting(SaasDomain(), 'NOCAPTCHA_FOR_CONTACT', $contact_status);

        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function student_setup()
    {
        try {
            $data = StudentSetup::getData();
            return view('setting::studentSetup', compact('data'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }

    public function student_setup_update(Request $request)
    {
        try {
            $data = StudentSetup::getData();
            $data->show_running_course_thumb = $request->show_running_course_thumb;
            $data->show_recommended_section = $request->show_recommended_section;
            $data->save();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return view('setting::studentSetup', compact('data'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }

    public function instructor_setup()
    {
        try {
            $data = InstructorSetup::getData();
            return view('setting::instructorSetup', compact('data'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }

    public function instructor_setup_update(Request $request)
    {
        try {
            $data = InstructorSetup::first();
            $data->show_instructor_page_banner = $request->show_instructor_page_banner;
            $data->save();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return view('setting::instructorSetup', compact('data'));
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());

        }
    }


    public function socialLogin()
    {
        return view('setting::socialLogin');
    }

    public function socialLoginStore(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }

        $allow_google_login = $request->get('allow_google_login');
        $google_client_id = $request->get('google_client_id');
        $google_secret_key = $request->get('google_secret_key');

        $allow_facebook_login = $request->get('allow_facebook_login');
        $facebook_client_id = $request->get('facebook_client_id');
        $facebook_secret_key = $request->get('facebook_secret_key');

        SaasEnvSetting(SaasDomain(), 'GOOGLE_CLIENT_ID', $google_client_id);
        SaasEnvSetting(SaasDomain(), 'GOOGLE_CLIENT_SECRET', $google_secret_key);


        SaasEnvSetting(SaasDomain(), 'FACEBOOK_CLIENT_ID', $facebook_client_id);
        SaasEnvSetting(SaasDomain(), 'FACEBOOK_CLIENT_SECRET', $facebook_secret_key);


        if ($allow_google_login == 1) {
            $login_status = 'true';
        } else {
            $login_status = 'false';
        }
        SaasEnvSetting(SaasDomain(), 'ALLOW_GOOGLE_LOGIN', $login_status);

        if ($allow_facebook_login == 1) {
            $allow_facebook_login = 'true';
        } else {
            $allow_facebook_login = 'false';
        }
        SaasEnvSetting(SaasDomain(), 'ALLOW_FACEBOOK_LOGIN', $allow_facebook_login);


        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }


    public function migration()
    {
        $migrations = [];
        $migrationsFolderPath[] = database_path('/migrations');
        $allModules = Module::all();
        foreach ($allModules as $name => $module) {
            if (isModuleActive($module)) {
                $migrationsFolderPath[] = $module->getPath() . '/Database/Migrations';
            }
        }
        foreach ($migrationsFolderPath as $path) {
            $files = app('migrator')->getMigrationFiles($path);
            foreach ($files as $key => $file) {
                $migrations[$key] = $file;
            }
        }
        $pendingMigrations = [];
        foreach ($migrations as $migration => $fullpath) {
            if (!DB::table('migrations')->where('migration', $migration)->exists())
                $pendingMigrations[$migration] = $fullpath;
        }
        return view('setting::migration', compact('pendingMigrations'));
    }

    public function migrationSubmit(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        if (auth()->user()->role_id != 1) {
            abort(403);
        }

        if ($request->password == "") {
            Toastr::error(__('common.enter_your_password'));
        } elseif (Hash::check($request->password, auth()->user()->password)) {
            Artisan::call('migrate',[
                '--no-interaction' => true,
            ]);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        } else {
            Toastr::error(__('common.Password did not match with your account password'));
        }
        return redirect()->back();
    }

    public function update_settings(Request $request)
    {
        $currency = Currency::where('id', $request->get('currency_id', 0))->first();
        if ($currency) {
            $currency_symbol = $currency->symbol;
            $currency_code = $currency->code;
        } else {
            $currency_symbol = Settings('currency_symbol');
            $currency_code = Settings('currency_code');
        }

        $data = [
            'currency_id' => $request->get('currency_id', 0),
            'currency_show' => $request->get('currency_show', 0),
            'currency_seperator' => $request->get('currency_seperator', 0),
            'currency_decimal' => $request->get('currency_decimal', 0),
            'hide_multicurrency' => $request->get('hide_multicurrency', 0),
            'currency_conversion' => $request->get('currency_conversion', 0),
            'currency_symbol' => $currency_symbol,
            'currency_code' => $currency_code,
            'currency_api_cache_time' => $request->get('currency_api_cache_time', 1440),

        ];
        $this->generalSettingRepository->update($data);
        Toastr::success(trans('common.Operation successful'), trans('common.Success'));
        return redirect()->back();
    }

    public function feedbackSettings()
    {
        $feedbacks = Feedbacks::get();
        //  print_r($feedbacks); exit;
        return view('setting::feedbacksettings', compact('feedbacks'));
    }

    public function storeFeedback(Request $request)
    {
        $feedback = Feedbacks::create([
            'feedback_name' => $request->feedback_name,
            'course_id' => 0,
            'status' => 0
        ]);

        return response()->json($feedback);
    }

   

    public function ajaxSaveReminder(Request $request)
    {
        //  return $request->all();
        $feedbackreminders = FeedbackReminders::updateOrCreate(
            ['feedback_id' => $request->feedback_id],
            [
                'feedback_id' => $request->feedback_id ?? 0,
                'course_id' => 0,
                'reminder_name' => null,
                'when_to_send' => $request->when ?? 0,
                'days_after_completion' => $request->days_after ?? 0,
                'recurrence' => $request->recurrence ?? 0,
                'for_days' => $request->for_days ?? 0,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Saved successfully',
            'data' => $feedbackreminders
        ]);
    }

    public function setActiveFeedback(Request $request)
    {
        // Step 1: Set all others to 0
        Feedbacks::where('id', '!=', $request->feedback_id)
            ->update(['status' => 0]);

        // Step 2: Set selected one to 1
        Feedbacks::where('id', $request->feedback_id)
            ->update(['status' => 1]);

        return response()->json(['success' => true]);
        
    }

    public function deleteFeedback(Request $request)
    {
        Feedbacks::where('id', $request->feedback_id)->delete();

        return response()->json(['success' => true]);
    }

    
    public function announcements()
    {
        
        return view('setting::announcements');
    }

    public function storeAnnouncement(Request $request)
    {
        $user_id = Auth::user()->id;
        $org_id =  Auth::user()->organization_id;
        Announcements::create([
            'title' => $request->title,
            'description' => $request->description,
            'announce_link' => $request->link,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => $user_id,
            'organization_id' => $org_id
        ]);

        return response()->json(['success' => true]);
    }

    public function getAnnouncementData(Request $request)
    {
        $query = Announcements::where('organization_id', Auth::user()->organization_id);

        // ✅ Search filter
        if (!empty($request->search['value'])) {
            $query->where('title', 'like', '%' . $request->search['value'] . '%');
        }

        // ✅ Date filters (correct fields)
        if ($request->start_date != "") {
            $query->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->end_date != "") {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        return Datatables::of($query)
            ->addIndexColumn()
             ->addColumn('checkbox', function ($query) {
                    $userid = $query->id;
                    return view(
                        'setting::page_components._td_bulk_checkbox',
                        compact('query', 'userid')
                    );
                })
            ->editColumn('title', function ($q) {
                return $q->title ?? '-';
            })

            ->editColumn('description', function ($q) {
                return $q->description ?? '-';
            })

            ->editColumn('start_date', function ($q) {
                return $q->start_date ?? '-';
            })

            ->editColumn('end_date', function ($q) {
                return $q->end_date ?? '-';
            })

            ->editColumn('created_by', function ($q) {
                return $q->created_by ?? '-';
            })

           ->addColumn('action', function ($q) {
                return view('setting::page_components._course_action_td', ['query' => $q])->render();
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    
    
    
    public function search_users_data(Request $request)
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

        return response()->json([
            'results' => $results
        ]);
    }

    public function assignUsers(Request $request)
    {
        $groups = $request->groups;
        $orgId = auth()->user()->organization_id;

        $finalUsers = null;

        foreach ($groups as $value) {

            [$type, $id] = explode('_', $value);

            $query = User::where('organization_id', $orgId);

            if ($type == 'bu') {
                $query->where('bu', $id);
            }

            else if ($type == 'dept') {
                $query->where('dept_id', $id);
            }

           else if ($type == 'profile') {
                $query->where('profile_id', $id);
            }

            else if ($type == 'country') {
                $query->where('country', $id);
            }

            else if ($type == 'state') {
                $query->where('state', $id);
            }

            else if ($type == 'city') {
                $query->where('city', $id);
            }

            else if ($type == 'location') {
                $query->where('location_code', $id);
            }

            else if ($type == 'manager') {
                $manager = User::find($id);
                if ($manager) {
                    $query->where('manager_email', $manager->email);
                }
            }

            $users = $query->pluck('id');

            

            if (is_null($finalUsers)) {
                $finalUsers = $users;
            } else {
                $finalUsers = $finalUsers->merge($users);;
            }
        }
        // remove duplicates
        // $finalUsers = $finalUsers->unique();
        // return $finalUsers;

        // ❌ No users case
        if (!$finalUsers || $finalUsers->isEmpty()) {
            return response()->json(['error' => 'No users found'], 400);
        }

        // ✅ Store in pivot table
        $data = [];

        $announcemnt_ids = explode(',', $request->announcement_id);
        foreach ($announcemnt_ids as $annoucne_id) {
            foreach ($finalUsers as $userId) {
                $data[] = [
                    'announcement_id' => $annoucne_id,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        

        DB::table('announcement_user')->insert($data);

        return response()->json(['success' => true]);
    }

    public function webpage_index()
    {
        $page = Tenants::where('id', Auth::user()->organization_id)->first();
        return view('setting::webpage_setting', compact('page'));
    }

    public function webpage_preview()
    {
        return view('setting::webpage_preview');
    }

    public function webpage_preview_store(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $data = $request->except('_token');
            foreach ($data as $key => $value) {
                UpdateGeneralSetting($key, $value);
            }
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }   
    }

    public function webpage_preview_remove(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $key = $request->key;
            UpdateGeneralSetting($key, null);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {                
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function getAllTenantData(Request $request)
    {
        $tenant_id = $request->tenant_id;

        $data = Tenants::with(['tenant_reviews', 'tenant_courses'])
            ->withCount('tenant_courses')
            ->find($tenant_id);

        return response()->json([
            'tenant' => $data,
            'courses' => $data->tenant_courses,
        ]);
    }

    public function webPageUpdate(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $rules = [
                'tenant_email' => 'required|email|unique:tenant_list,tenant_email,' . $request->tenant_id,
                'tenant_name' => 'required',
                'phone' => 'required',
                'tenant_slogan' => 'required',
                'tenant_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'tenant_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'fav_icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico',
                'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'brouchure' => 'nullable|file|mimes:pdf,doc,docx',
            ];

            $messages = [
                'tenant_slogan.required' => 'Tenant Slogan is required.',
                'tenant_name.required' => 'Tenant Name is required.',
                'phone.required' => 'Phone is required.',
                'tenant_email.required' => 'Email is required.',
                'tenant_email.email' => 'Email must be a valid email address.',
                'tenant_email.unique' => 'Email has already been taken.',
                'tenant_banner.image' => 'Tenant Banner must be an image.',
                'tenant_banner.mimes' => 'Tenant Banner must be a file of type: jpeg, png, jpg, gif, svg.',
                'tenant_banner.max' => 'Tenant Banner may not be greater than 1024 kilobytes.',
                'tenant_logo.image' => 'Tenant Logo must be an image.',
                'tenant_logo.mimes' => 'Tenant Logo must be a file of type: jpeg, png, jpg, gif, svg.',
                'tenant_logo.max' => 'Tenant Logo may not be greater than 1024 kilobytes.',
                'fav_icon.image' => 'Favicon must be an image.',
                'fav_icon.mimes' => 'Favicon must be a file of type: jpeg, png, jpg, gif, svg, ico.',
                'fav_icon.max' => 'Favicon may not be greater than 1024 kilobytes.',
                'footer_logo.image' => 'Footer Logo must be an image.',
                'footer_logo.mimes' => 'Footer Logo must be a file of type: jpeg, png, jpg, gif, svg.',
                'footer_logo.max' => 'Footer Logo may not be greater than 1024 kilobytes.',
                'brouchure.file' => 'Brouchure must be a file.',
                'brouchure.mimes' => 'Brouchure must be a file of type: pdf, doc, docx.',
                'brouchure.max' => 'Brouchure may not be greater than 1024 kilobytes.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            
            $id = $request->tenant_id;
            $tenant = Tenants::find($id);
            // Update tenant data
            if ($request->hasFile('tenant_banner')) {

                $bannerPath = $this->uploadMedia($request->file('tenant_banner'), 'tenant_banners');
                $request->merge(['tenant_banner' => $bannerPath]);
            }

            if ($request->hasFile('tenant_logo')) {
                $logoPath = $this->uploadMedia($request->file('tenant_logo'), 'tenant_logos');
                $request->merge(['tenant_logo' => $logoPath]);
            }

            if ($request->hasFile('fav_icon')) {
                $faviconPath = $this->uploadMedia($request->file('fav_icon'), 'tenant_favicons');
                $request->merge(['fav_icon' => $faviconPath]);
            }

            if ($request->hasFile('footer_logo')) {
                $footerLogoPath = $this->uploadMedia($request->file('footer_logo'), 'tenant_footer_logos');
                $request->merge(['footer_logo' => $footerLogoPath]);
            }

            if ($request->hasFile('brouchure')) {
                $brouchurePath = $this->uploadMedia($request->file('brouchure'), 'tenant_brouchures');
                $request->merge(['brouchure' => $brouchurePath]);
            }

            $updated = $tenant->update($request->except('_token', 'tenant_id'));
            if ($updated) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Operation done successfully'
                ]);
            }
           
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }   
    }

    public function tenant_galleries()
    {
        $tenant_id = Auth::user()->organization_id;
        $galleries = TenantGallery::where('organization_id', $tenant_id)->get();
        return view('setting::tenant_galleries', compact('galleries'));
    }

    public function tenant_gallery_delete($id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $gallery = TenantGallery::findOrFail($id);
            $gallery->delete();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function tenant_reviews()
    {
        $tenant_id = Auth::user()->organization_id;
        $reviews = TenantReviews::where('organization_id', $tenant_id)->get();
        return view('setting::tenant_reviews', compact('reviews'));
    }

    public function tenant_review_create()
    {
        return view('setting::tenant_review_create');
    }

    public function tenant_galleries_store(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $tenant_id = Auth::user()->organization_id;
            $media = MediaManager::find($request->image);

            TenantGallery::create([
                'organization_id' => $tenant_id,
                'image_url' => $media->file_name, // or $media->url, depending on your table
                'status' => $request->status,
            ]);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }   
    }

    public function tenant_gallery_edit($id)
    {
        $gallery = TenantGallery::findOrFail($id);
        return view('setting::tenant_gallery_edit', compact('gallery'));
    }

    public function tenant_gallery_update(Request $request, $id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $gallery = TenantGallery::findOrFail($id);
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadMedia($request->file('image'), 'tenant_gallery');
                $request->merge(['image' => $imagePath]);
            }
            $gallery->update($request->except('_token'));
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->route('setting.tenant_galleries');
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }   

    }
    
    public function tenant_reviews_add()
    {   
        return view('setting::tenant_review_create');
    }

    public function tenant_reviews_store(Request $request)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            $tenant_id = Auth::user()->organization_id;
            TenantReviews::create([
                'organization_id' => $tenant_id,
                'reviewer_id' => 54,
                'review_text' => $request->description,
                'rating' => $request->rating,
                'status' => 1,
            ]);
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }
    }

    public function usertype_change_panel_update($tenant_slug = '', $id)
    {
        if (demoCheck()) {
            return redirect()->back();
        }
        try {
            // dd($id); 
            $user = Auth::user();
            
            $role_id = ($id == 14) ? 3 : $id; // Assuming 3 is the role ID for 'Instructor' and 14 is for 'Learner'
            $user->role_id = $role_id;
            $user->save();
            Toastr::success(trans('common.Operation successful'), trans('common.Success'));
            return redirect()->back();
        } catch (Exception $e) {
            GettingError($e->getMessage(), url()->current(), request()->ip(), request()->userAgent());
        }   
    }
}
