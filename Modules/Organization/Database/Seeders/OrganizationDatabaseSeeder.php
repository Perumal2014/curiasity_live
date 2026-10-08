<?php

namespace Modules\Organization\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\RolePermission;

class OrganizationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $users = [
            [
                'name' => 'John Doe - Tech Innovators Ltd.',
                'about' => 'Software Developer at Tech Innovators Ltd.',
                'email' => 'johndoe@techinnovators.com',
                'password' => bcrypt('12345678'),
             ],
            [
                'name' => 'Jane Smith - Digital Marketing Co.',
                'about' => 'Digital Marketing Specialist at Digital Marketing Co.',
                'email' => 'janesmith@digitalmarketing.com',
                'password' => bcrypt('12345678'),

            ],
            [
                'name' => 'Robert Johnson - Project Leaders Inc.',
                'about' => 'Project Manager at Project Leaders Inc.',
                'email' => 'robertjohnson@projectleaders.com',
                'password' => bcrypt('12345678'),

            ],
            [
                'name' => 'Alice Brown - Creative Minds Studio',
                'about' => 'Graphic Designer at Creative Minds Studio.',
                'email' => 'alicebrown@creativeminds.com',
                'password' => bcrypt('12345678'),

            ],
            [
                'name' => 'Michael Lee - Innovate Solutions',
                'about' => 'Tech Consultant at Innovate Solutions.',
                'email' => 'michaellee@innovatesolutions.com',
                'password' => bcrypt('12345678'),

            ],
        ];
        foreach ( $users as $userData) {
            $user = new User;
            $user->name = $userData['name'];
            $user->email =$userData['email'];
            $user->username = null;
            $user->password = $userData['password'];
            $user->about =$userData['about'];
            $user->dob = '';
            $user->phone = null;

            $user->language_id = '19';
            $user->language_code = 'en';
            $user->language_name = 'English';
            $user->language_rtl = 0;
            $user->country = 19;


            $user->email_verify = 1;
            $user->email_verified_at = now();
            $user->lms_id = 1;

            $user->role_id = 5;
            $user->referral = generateUniqueId();
            $user->special_commission = null;
            $user->save();

            $this->assignPermissions($user);
            applyDefaultRoleToUser($user);
            assignStaffToUser($user);
        }
    }

    public function assignPermissions($user)
    {
//dashboard,courses,quiz,class,blog
        $mainRoutes =['dashboard','courses','quiz','virtual-class','blogs'];

        foreach ($mainRoutes as $route) {
            $permission = Permission::with('childs')->where('route', $route)->first();
            if (!$permission) {
                RolePermission::updateOrCreate([
                    'role_id'=>$user->role_id,
                    'permission_id'=>$permission->id
                ]);

               $childs = $permission->childs;
               foreach ($childs as $child) {
                   RolePermission::updateOrCreate([
                        'role_id'=>$user->role_id,
                        'permission_id'=>$child->id
                   ]);
               }

            }
        }
    }

}
