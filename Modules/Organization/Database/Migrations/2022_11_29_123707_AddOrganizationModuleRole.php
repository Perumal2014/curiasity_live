<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\Permission;
use Modules\RolePermission\Entities\RolePermission;

class AddOrganizationModuleRole extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->integer('organization_id')->nullable();
            }
        });

        $routes = [
            ['name' => 'Organization', 'route' => 'organization', 'type' => 1, 'parent_route' => null, 'module' => 'Organization'],
            ['name' => 'List', 'route' => 'organization.index', 'type' => 2, 'parent_route' => 'organization', 'module' => 'Organization'],
            ['name' => 'Add', 'route' => 'organization.store', 'type' => 3, 'parent_route' => 'organization.index', 'module' => 'Organization'],
            ['name' => 'Edit', 'route' => 'organization.update', 'type' => 3, 'parent_route' => 'organization.index', 'module' => 'Organization'],
            ['name' => 'Delete', 'route' => 'organization.destroy', 'type' => 3, 'parent_route' => 'organization.index', 'module' => 'Organization'],
            ['name' => 'Change Status', 'route' => 'organization.changeStatus', 'type' => 3, 'parent_route' => 'organization.index', 'module' => 'Organization'],

        ];
        if (function_exists('permissionUpdateOrCreate')) {
            permissionUpdateOrCreate($routes);
        }

        // assign role to user

        RolePermission::where('role_id', 5)->delete();

        $routes = [
            0 => 'dashboard',
            1 => 'dashboard.number_of_instructor',
            2 => 'dashboard.number_of_enrolled',
            3 => 'dashboard.number_of_subject',
            4 => 'dashboard.total_revenue',
            5 => 'dashboard.total_enrolled_today',
            6 => 'dashboard.total_enrolled_this_month',
            7 => 'dashboard.total_amount_from_enrolled',
            8 => 'dashboard.recent_enrolls',
            9 => 'dashboard.overview_of_courses',
            10 => 'dashboard.number_of_student',
            11 => 'dashboard.overview_status_of_courses',
            12 => 'users.my_panel.index',
            13 => 'users.my_topics.index',
            14 => 'users.deposit.index',
            15 => 'users.my_certificates.index',
            16 => 'users.logged_in_devices.index',
            17 => 'users.my_referral.index',
            18 => 'users.my_purchase.index',
            19 => 'users.my_refund.index',
            20 => 'organization.financial.index',
            21 => 'organization.sales_report.index',
            22 => 'organization.financial_report.index',
            23 => 'organization.payout.index',
            24 => 'organization.payout.store',
            25 => 'organization.payout.completed',
            26 => 'students',
            27 => 'student.student_list',
            28 => 'student.store',
            29 => 'student.edit',
            30 => 'student.delete',
            31 => 'student.change_status',
            32 => 'student.courses',
            33 => 'instructors',
            34 => 'allInstructor',
            35 => 'instructor.store',
            36 => 'instructor.edit',
            37 => 'instructor.delete',
            38 => 'instructor.change_status',
            39 => 'courses',
            40 => 'course.category',
            41 => 'course.category.store',
            42 => 'course.category.edit',
            43 => 'course.category.delete',
            44 => 'course.category.status_update',
            45 => 'getAllCourse',
            46 => 'course.enrolled_students',
            47 => 'course.courseInvitation',
            48 => 'course.courseStudentNotify',
            49 => 'course.store',
            50 => 'course.edit',
            51 => 'courseDetails',
            52 => 'course.view',
            53 => 'course.status_update',
            54 => 'course.delete',
            55 => 'course-level.index',
            56 => 'course-level.store',
            57 => 'course-level.update',
            58 => 'course-level.destroy',
            59 => 'course-level.changeStatus',
            60 => 'quiz',
            61 => 'question-group',
            62 => 'question-group.store',
            63 => 'question-group.edit',
            64 => 'question-group.delete',
            65 => 'question-bank',
            66 => 'question-bank.store',
            67 => 'question-bank.edit',
            68 => 'question-bank.delete',
            69 => 'question-bank-list',
            70 => 'online-quiz',
            71 => 'set-quiz.store',
            72 => 'set-quiz.edit',
            73 => 'set-quiz.delete',
            74 => 'set-quiz.set-question',
            75 => 'set-quiz.manage-question',
            76 => 'set-quiz.publish-now',
            77 => 'set-quiz.mark-register',
            78 => 'set-quiz.enrolled-student',
            79 => 'quizReTest',
            80 => 'set-quiz.quiz_result',
            81 => 'quizResult',
            82 => 'virtual-class',
            83 => 'virtual-class.index',
            84 => 'virtual-class.create',
            85 => 'virtual-class.edit',
            86 => 'virtual-class.destroy',
            87 => 'zoom',
            88 => 'zoom.settings',
            89 => 'certificate',
            90 => 'certificate.index',
            91 => 'certificate.edit',
            92 => 'certificate.destroy',
            93 => 'certificate.create',
            94 => 'enrollment',
            95 => 'student.new_enroll',
            96 => 'admin.enrollLogs',
            97 => 'admin.cancelLogs',
            98 => 'refund.approved',
            99 => 'refund.reject',
            100 => 'coupons',
            101 => 'coupons.manage',
            102 => 'coupons.store',
            103 => 'coupons.edit',
            104 => 'coupons.delete',
            105 => 'coupons.status_update',
            106 => 'coupons.common',
            107 => 'coupons.common.store',
            108 => 'coupons.common.edit',
            109 => 'coupons.common.delete',
            110 => 'coupons.common.status_update',
            111 => 'coupons.single',
            112 => 'coupons.single.store',
            113 => 'coupons.single.edit',
            114 => 'coupons.single.delete',
            115 => 'coupons.single.status_update',
            116 => 'coupons.personalized',
            117 => 'coupons.personalized.store',
            118 => 'coupons.personalized.edit',
            119 => 'coupons.personalized.delete',
            120 => 'coupons.personalized.status_update',
            121 => 'coupons.invite_code',
            122 => 'coupons.invite_code.edit',
            123 => 'coupons.invite_code.delete',
            124 => 'coupons.invite_code.status_update',
            125 => 'frontend.ContactPageContent',
            126 => 'popup-content.index',
            127 => 'frontend.faq.index',
            128 => 'blogs',
            129 => 'blog-category.index',
            130 => 'blog-category.store',
            131 => 'blog-category.update',
            132 => 'blog-category.destroy',
            133 => 'blog-category.changeStatus',
            134 => 'blogs.index',
            135 => 'blogs.store',
            136 => 'blogs.update',
            137 => 'blogs.destroy',
            138 => 'blogs.changeStatus',
            139 => 'communications',
            140 => 'communication.PrivateMessage',
            141 => 'communication.send',
            142 => 'comments',
            143 => 'blogs.comments.index',
            144 => 'blogs.comments.destroy',
            145 => 'blogs.comments.reply',
            146 => 'topics.comments.index',
            147 => 'topics.comments.destroy',
            148 => 'topics.comments.reply',
            149 => 'notification',
            150 => 'notifications.posted.index',
            151 => 'notifications.posted.create',
            152 => 'notifications.posted.destroy',
        ];
        foreach ($routes as $route) {
            $permission = Permission::select('id', 'route')->where('route', $route)->first();
            if ($permission) {
                RolePermission::create(
                    [
                        'role_id' => 5,
                        'permission_id' => $permission->id,
                        'created_at' => now(),
                        'updated_at' => now(),

                    ]);
            }

        }
        Artisan::call('cache:clear',[
            '--no-interaction' => true,
        ]);
    }

    public function down()
    {
        //
    }
}
