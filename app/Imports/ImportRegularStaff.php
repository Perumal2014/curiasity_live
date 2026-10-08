<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

use App\Models\Tenants;
use Modules\SystemSetting\Entities\Staff;

use App\Models\BusinessUnit;
use App\Models\Department;
use App\Models\Profile;
use App\Models\Management;

use App\Models\AssociateState;
use App\Models\AssociateCity;
use App\Models\LocationCode;
use App\Models\AssociateCountry;

use App\Models\Skills;
use App\Models\UserSkills;

class ImportRegularStaff implements ToCollection, WithHeadingRow
{
    public function collection(Collection $items)
    {
        $tenant = Tenants::find(Auth::user()->organization_id);

        foreach ($items as $row) {

            /*
            |--------------------------------------------------------------------------
            | Clean Values
            |--------------------------------------------------------------------------
            */

            $buName            = trim($row['bu'] ?? '');
            $deptName          = trim($row['department'] ?? '');
            $profileName       = trim($row['profile'] ?? '');
            $countryName       = trim($row['associate_country'] ?? '');
            $stateName         = trim($row['office_state'] ?? '');
            $cityName          = trim($row['office_city'] ?? '');
            $reporting_manager = trim($row['reporting_manager'] ?? '');

            $primary_skills    = trim($row['primary_skills'] ?? '');
            $secondary_skills  = trim($row['secondary_skills'] ?? '');

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            $profile = Profile::firstOrCreate(
                [
                    'profile_name'    => $profileName,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 1
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Business Unit
            |--------------------------------------------------------------------------
            */

            $bu = BusinessUnit::firstOrCreate(
                [
                    'bu_name'         => $buName,
                    'profile_id'      => $profile->id,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 2
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            $dept = Department::firstOrCreate(
                [
                    'dept_name'       => $deptName,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'bu_id'    => $bu->id,
                    'grp_type' => 3
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Country
            |--------------------------------------------------------------------------
            */

            $country = AssociateCountry::firstOrCreate(
                [
                    'country_name'    => $countryName,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 4
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | State
            |--------------------------------------------------------------------------
            */

            $state = AssociateState::firstOrCreate(
                [
                    'state_name'      => $stateName,
                    'country_id'      => $country->id,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 5
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | City
            |--------------------------------------------------------------------------
            */

            $city = AssociateCity::firstOrCreate(
                [
                    'city_name'       => $cityName,
                    'state_id'        => $state->id,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 6
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Location Code
            |--------------------------------------------------------------------------
            */

            $location = LocationCode::firstOrCreate(
                [
                    'location_code'   => $row['location_code'] ?? '',
                    'country_id'      => $country->id,
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 7
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Management
            |--------------------------------------------------------------------------
            */

            $management = Management::firstOrCreate(
                [
                    'management_name' => $row['management_level'] ?? '',
                    'organization_id' => Auth::user()->organization_id
                ],
                [
                    'status'   => 1,
                    'grp_type' => 8
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | User Data
            |--------------------------------------------------------------------------
            */

            $name  = trim($row['name'] ?? '');
            $email = strtolower(trim($row['email'] ?? ''));

            if (!$name || !$email) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Skip Existing User
            |--------------------------------------------------------------------------
            */

            if (User::where('email', $email)->exists()) {
                continue;
            }

            $plainPassword = 'nbtc@123!';

            $role_id = (Auth::user()->tenant_id == CORPORATE) ? 3 : 9;

            /*
            |--------------------------------------------------------------------------
            | Prepare Skills
            |--------------------------------------------------------------------------
            */

            $primarySkills = array_unique(array_filter(
                array_map(function ($skill) {
                    return strtolower(trim($skill));
                }, explode(',', $primary_skills))
            ));

            $secondarySkills = array_unique(array_filter(
                array_map(function ($skill) {
                    return strtolower(trim($skill));
                }, explode(',', $secondary_skills))
            ));

            /*
            |--------------------------------------------------------------------------
            | Store Skills and Collect IDs
            |--------------------------------------------------------------------------
            */

            $skillIds = [];

            /*
            |--------------------------------------------------------------------------
            | Primary Skills => type = 1
            |--------------------------------------------------------------------------
            */

            foreach ($primarySkills as $skill) {

                $skillRow = Skills::firstOrCreate(
                    [
                        'name' => $skill,
                        'type' => 1,
                    ]
                );

                $skillIds[] = $skillRow->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Secondary Skills => type = 2
            |--------------------------------------------------------------------------
            */

            foreach ($secondarySkills as $skill) {

                $skillRow = Skills::firstOrCreate(
                    [
                        'name' => $skill,
                        'type' => 2,
                    ]
                );

                $skillIds[] = $skillRow->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Remove Duplicate Skill IDs
            |--------------------------------------------------------------------------
            */

            $skillIds = array_unique($skillIds);

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'user_type'           => 1,
                'name'                => $name,
                'email'               => $email,
                'gender'              => strtolower($row['gender'] ?? 'male'),
                'username'            => $email,
                'password'            => Hash::make($plainPassword),

                'role_id'             => $role_id,
                'organization_id'     => $tenant->id,
                'tenant_id'           => $tenant->tenant_type,

                'manager_email'       => strtolower(trim($row['manager'] ?? '')),

                'employee_id'         => $row['employee_id'] ?? null,

                'email_verified_at'   => now(),

                'bu'                  => $bu->id,
                'dept_id'             => $dept->id,
                'profile_id'          => $profile->id,

                'country'             => $country->id,
                'state'               => $state->id,
                'city'                => $city->id,

                'location_code'       => $location->id,

                'mgt_id'              => $management->id,

                'date_of_join'        => $row['date_of_joining'] ?? null,

                'cust_id'             => $row['customer_id'] ?? null,

                'data_source'         => $row['data_source'] ?? null,

                'reporting_manager_id'=> $reporting_manager,

                'experience'          => $row['experience'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Store User Skills
            |--------------------------------------------------------------------------
            */

            if ($user) {

                foreach ($skillIds as $skillId) {

                    UserSkills::firstOrCreate([
                        'user_id'  => $user->id,
                        'skill_id' => $skillId,
                    ]);
                }

                $slug1 = session('tenant_slug');

                $data = [
                    'email' => $email,
                    'password' => $plainPassword,
                    'login' => route('tenant.login', ['tenant_slug' => $slug1]),
                    'year' => '2026-27',
                    'footer' => 'Warm Welcome'
                ];

                // send_credential_email($user, 'type', $data, 'shortcodes');

                $user->update([
                    'mail_status' => 1,
                    'mail_sent_at' => now()
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Staff Table
            |--------------------------------------------------------------------------
            */

            if ($user && Auth::user()->tenant_id != CORPORATE) {

                Staff::create([
                    'user_id'       => $user->id,
                    'department_id' => $dept->id,
                    'phone'         => $row['phone'] ?? null,
                    'qualification' => $row['qualification'] ?? null,
                    'subject'       => $row['subject'] ?? null
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Apply Default Role
            |--------------------------------------------------------------------------
            */

            if ($user) {
                applyDefaultRoleToUser($user);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Reporting Manager Mapping
        |--------------------------------------------------------------------------
        */

        $allUsers = User::where('organization_id', Auth::user()->organization_id)
            ->get()
            ->keyBy('email');

        foreach ($allUsers as $user) {

            if (!empty($user->manager_email) && isset($allUsers[$user->manager_email])) {

                $manager = $allUsers[$user->manager_email];

                $user->update([
                    'reporting_manager_id' => $manager->employee_id ?? null,
                ]);

                $managerEmployeeIds = User::where('organization_id', $user->organization_id)
                    ->whereNotNull('reporting_manager_id')
                    ->pluck('reporting_manager_id')
                    ->unique()
                    ->toArray();

                /*
                |--------------------------------------------------------------------------
                | Employee Role
                |--------------------------------------------------------------------------
                */

                User::where('organization_id', $user->organization_id)
                    ->where('reset_role', '!=', 1)
                    ->where('user_type', '!=', 0)
                    ->update([
                        'role_id'    => 3,
                        'reset_role' => 1
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Manager Role
                |--------------------------------------------------------------------------
                */

                User::where('organization_id', $user->organization_id)
                    ->whereNotIn('role_id', ['11'])
                    ->whereIn('employee_id', $managerEmployeeIds)
                    ->update([
                        'role_id' => 13
                    ]);
            }
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}