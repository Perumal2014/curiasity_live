<?php

namespace App\Imports;

use App\Models\User;
use Modules\StudentSetting\Entities\StaffDetail;
use App\Models\Tenants;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ImportStaff implements ToCollection, WithStartRow, WithHeadingRow
{
    public $duplicateEmails = [];
    public $duplicatePhones = [];
    public $importedEmails = [];
    public $failedRows = [];

    public function collection(Collection $items)
    {
        $tenant = Tenants::find(Auth::user()->organization_id);

        /*
        |--------------------------------------------------------------------------
        | Keep track of emails/phones from current Excel file
        |--------------------------------------------------------------------------
        */
        $excelEmails = [];
        $excelPhones = [];

        foreach ($items as $row) {

            try {

                $name = trim($row['name'] ?? '');
                $dob = trim($row['dob'] ?? '');
                $phone = trim($row['phone'] ?? '');
                $handle_year = trim($row['handle_year'] ?? '');
                $email = strtolower(trim($row['email'] ?? ''));
                $gender = strtolower(trim($row['gender'] ?? ''));
                $address = trim($row['address'] ?? '');
                $department = trim($row['department'] ?? '');

                /*
                |--------------------------------------------------------------------------
                | Validate required fields
                |--------------------------------------------------------------------------
                */

                if (empty($name)) {
                    $this->failedRows[] = [
                        'row' => $row->toArray(),
                        'reason' => 'Name is missing',
                    ];

                    continue;
                }

                if (empty($email)) {
                    $this->failedRows[] = [
                        'row' => $row->toArray(),
                        'reason' => 'Email is missing',
                    ];

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Duplicate email inside Excel
                |--------------------------------------------------------------------------
                */

                if (in_array($email, $excelEmails)) {

                    $this->duplicateEmails[] = [
                        'email' => $email,
                        'reason' => 'Duplicate email in uploaded file',
                    ];

                    continue;
                }

                $excelEmails[] = $email;


                /*
                |--------------------------------------------------------------------------
                | Duplicate email in database
                |--------------------------------------------------------------------------
                */

                if (User::where('email', $email)->exists()) {

                    $this->duplicateEmails[] = [
                        'email' => $email,
                        'reason' => 'Email already exists',
                    ];

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Duplicate phone inside Excel
                |--------------------------------------------------------------------------
                */

                if (!empty($phone)) {

                    if (in_array($phone, $excelPhones)) {

                        $this->duplicatePhones[] = [
                            'phone' => $phone,
                            'reason' => 'Duplicate phone in uploaded file',
                        ];

                        continue;
                    }

                    $excelPhones[] = $phone;


                    /*
                    |--------------------------------------------------------------------------
                    | Duplicate phone in database
                    |--------------------------------------------------------------------------
                    */

                    if (User::where('phone', $phone)->exists()) {

                        $this->duplicatePhones[] = [
                            'phone' => $phone,
                            'reason' => 'Phone already exists',
                        ];

                        continue;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Generate password
                |--------------------------------------------------------------------------
                */

                $plainPassword = Str::random(10);

                $password = Hash::make($plainPassword);


                /*
                |--------------------------------------------------------------------------
                | Create User
                |--------------------------------------------------------------------------
                */

                $user = User::create([
                    'name'              => $name,
                    'email'             => $email,
                    'username'          => $email,
                    'phone'             => $phone ?: null,
                    'gender'            => $gender ?: null,
                    'dob'               => $dob ?: null,
                    'password'          => $password,
                    'email_verified_at' => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                    'role_id'           => 9,
                    'referral'          => generateUniqueId(),
                    'language_id'       => Settings('language_id') ?? '19',
                    'language_name'     => Settings('language_name') ?? 'English',
                    'language_code'     => Settings('language_code') ?? 'en',
                    'language_rtl'      => Settings('language_rtl') ?? '0',
                    'country'           => Settings('country_id'),
                    'lms_id'            => Auth::user()->lms_id,
                    'organization_id'  => Auth::user()->organization_id ?? null,
                    'tenant_id'        => Auth::user()->tenant_id ?? null,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Create Staff Details
                |--------------------------------------------------------------------------
                */

                if ($user) {

                    $StaffDetail = new StaffDetail();

                    $StaffDetail->employee_id = $row['employee_id'] ?? null;
                    $StaffDetail->user_id = $user->id;
                    $StaffDetail->department_id = $department ?: null;
                    $StaffDetail->handle_year = $handle_year ?: null;
                    $StaffDetail->phone = $phone ?: null;
                    $StaffDetail->qualification = $row['qualification'] ?? null;

                    $StaffDetail->save();


                    /*
                    |--------------------------------------------------------------------------
                    | Apply Default Role
                    |--------------------------------------------------------------------------
                    */

                    applyDefaultRoleToUser($user);


                    /*
                    |--------------------------------------------------------------------------
                    | Send Credentials Email
                    |--------------------------------------------------------------------------
                    */

                    try {

                        $data = [
                            'email'    => $email,
                            'password' => $plainPassword,
                            'login'    => route('tenant.login', [
                                session('tenant_slug')
                            ]),
                            'year'     => '2026-27',
                            'footer'   => 'Warm Welcome',
                        ];

                        $notify = send_credential_email(
                            Auth::user(),
                            'type',
                            $data,
                            'shortcodes'
                        );

                    } catch (\Exception $mailException) {

                        Log::error(
                            'STAFF IMPORT MAIL ERROR: ' .
                            $mailException->getMessage()
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Successfully imported
                    |--------------------------------------------------------------------------
                    */

                    $this->importedEmails[] = $email;
                }

            } catch (\Exception $e) {

                Log::error(
                    'STAFF IMPORT ERROR: ' .
                    $e->getMessage()
                );

                $this->failedRows[] = [
                    'row' => $row->toArray(),
                    'reason' => $e->getMessage(),
                ];
            }
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function headingRow(): int
    {
        return 1;
    }
}