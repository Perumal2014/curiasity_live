<?php

namespace App\Imports;

use App\User;
use Modules\StudentSetting\Entities\StudentDetail;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;
use App\Models\Tenants;
use Modules\StudentSetting\Mail\StudentCredentialsMail;
use Illuminate\Support\Facades\Mail;
use Modules\SystemSetting\Entities\Department;

class ImportRegularStudent implements ToCollection, WithStartRow, WithHeadingRow
{
    public $duplicateEmails = [];

    public function collection(Collection $items)
    {
        
        $tenant = Tenants::find(Auth::user()->organization_id);
        // print_r($items); die;
            foreach ($items as $row) {
            $roll_no = $row['roll_no'] ?? '';
            $name = $row['name'] ?? '';
            $level = $row['level1_beginner_2_intermediate_3_advanced_4_pro'] ?? '';
            $year = $row['year'] ?? '';
            $dob = $row['dob'] ?? '';
            $phone = $row['phone'] ?? '';
            $parent_phone = $row['parent_number'] ?? '';
            $email = $row['email'] ?? '';
            $gender = $row['gender'] ?? '';
            $address = $row['address'] ?? '';
            $plainPassword = Str::random(10);     // e.g. "aZ9Kp3XqL2"
            $password = Hash::make($plainPassword);
            $password = $password; // generate if empty
            $auther = Auth::user()->organization_id;
            //print_r($auther); die;
            $dept = $row['department'] ?? '';
            //print_r($dept); die;
            $learning_level = $row['level1_beginner_2_intermediate_3_advanced_4_pro'] ?? '';
            $stud_year = $row['stud_year'] ?? 0;
            $parent_phone = $row['parent_number'] ?? 0;
            $stud_roll = $row['stud_roll_no'] ?? 0;
           
            $user = User::where('email', $email)->first();

            if (User::where('email', $email)->exists()) {
                    $this->duplicateEmails[] = $email;
                    continue;
                }
            if (User::where('phone', $phone)->exists()) {
                    $this->duplicateEmails[] = $phone;
                    continue;
                }

            $username = $email;

            $departmentName = trim($row['department'] ?? '');

            $deptCheck = Department::where('name', 'LIKE', '%' . $departmentName . '%')
                ->where('organization_id', Auth::user()->organization_id)
                ->first();

            $departmentId = $deptCheck?->id;

            // print_r($departmentId); die;


            if (!empty($email) && !empty($name) && !empty($password)) {
                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    continue; // Skip to the next iteration if user exists
                }

                            // Check duplicate email
                if (User::where('email', $email)->exists()) {
                    $this->duplicateEmails[] = $email;
                    continue;
                }

                
                $user = User::create([
                    'name' => $name,
                    'email' => strtolower($email),
                    'username' => $username,
                    'phone' => $phone ?? null,
                    'gender' => $gender ?? null,
                    'dob' => $dob ?? null,
                    'password' => $password,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'role_id' => 3, // corporate=4, student=3
                    'referral' => generateUniqueId(),
                    'language_id' => Settings('language_id') ?? '19',
                    'language_name' => Settings('language_name') ?? 'English',
                    'language_code' => Settings('language_code') ?? 'en',
                    'language_rtl' => Settings('language_rtl') ?? '0',
                    'country' => Settings('country_id'),
                    'lms_id' => Auth::user()->lms_id,
                    'organization_id' => Auth::user()->organization_id ?? null,
                    'tenant_id' => Auth::user()->tenant_id ?? null,
                ]);

                  if ($user) {
                    $data = array(
                    'email' => $email,
                    'password' => $plainPassword,
                    'login' => route('tenant.login', [session('tenant_slug')]),
                    'year' => '2026-27',
                    'footer' => 'Warm Welcome');
                }

                $notify = send_credential_email(Auth::user(), 'type', $data, 'shortcodes');   
                  
                $tenant_name = $tenant->tenant_name ?? 'XX';
                $clean = preg_replace('/[^A-Za-z]/', '', $tenant_name);
                $prefix = strtoupper(substr($clean, 0, 2));
                $number = str_pad($user->id, 6, '0', STR_PAD_LEFT);
                
                $studmoredetail = new StudentDetail();
                $studmoredetail->unique_code = $prefix . $number;
                $studmoredetail->user_id = $user->id;
                $studmoredetail->department_id = $departmentId ?? null;
                
                $studmoredetail->learning_level = $learning_level ?? null;
                $studmoredetail->student_year = $stud_year ?? null;
                $studmoredetail->parent_no = $parent_phone ?? null;
                $studmoredetail->student_roll_no = $stud_roll ?? null;

                // print_r($studmoredetail->department_id); die;

                $studmoredetail->save();
            
                applyDefaultRoleToUser($user);
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