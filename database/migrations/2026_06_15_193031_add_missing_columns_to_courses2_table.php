<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('courses2', 'has_external')) {
              Schema::table('courses2', function (Blueprint $table) {
           
            $table->tinyInteger('has_external')->default(0)->after('has_badge');
            $table->tinyInteger('has_certificate')->nullable()->after('has_external');

            $table->string('primary_skill_id')->nullable()->after('has_certificate');
            $table->string('secondary_skill_id')->nullable()->after('primary_skill_id');

            $table->tinyInteger('recommended_available')->default(0)->after('secondary_skill_id');
            $table->unsignedBigInteger('recommended_for')->nullable()->after('recommended_available');
            $table->string('recommend_dept')->nullable()->after('recommended_for');


            $table->string('delivery_mode')->nullable()->after('course_badge');
            $table->string('meeting_link')->nullable()->after('delivery_mode');

            $table->date('course_start_date')->nullable()->after('meeting_link');
            $table->date('course_end_date')->nullable()->after('course_start_date');

            $table->smallInteger('course_approve_level')->nullable()->after('course_end_date');
            $table->integer('course_approved_by')->nullable()->after('course_approve_level');

            $table->integer('feedback_available')->default(0)->after('course_approved_by');
            $table->tinyInteger('manager_feedback_available')->default(0)->after('feedback_available');

            $table->tinyInteger('has_recommended')->nullable()->after('manager_feedback_available');

            $table->string('recommend_role_id')->nullable()->after('has_recommended');
            $table->string('recommend_bu_id')->nullable()->after('recommend_role_id');
            $table->string('rec_department_id')->nullable()->after('recommend_bu_id');

            $table->string('grp_select_val')->nullable()->after('rec_department_id');
            $table->string('grp_business_unit')->nullable()->after('grp_select_val');
            $table->string('grp_department')->nullable()->after('grp_business_unit');
            $table->string('grp_state')->nullable()->after('grp_department');
            $table->string('grp_city')->nullable()->after('grp_state');
        });

        }
      
    }

    public function down(): void
    {
        if (Schema::hasColumn('courses2', 'has_external')) {
                Schema::table('courses2', function (Blueprint $table) {
                $table->dropColumn([
                    'has_external',
                    'has_certificate',
                    'primary_skill_id',
                    'secondary_skill_id',
                    'recommended_available',
                    'recommended_for',
                    'recommend_dept',
                    'delivery_mode',
                    'meeting_link',
                    'course_start_date',
                    'course_end_date',
                    'course_approve_level',
                    'course_approved_by',
                    'feedback_available',
                    'manager_feedback_available',
                    'has_recommended',
                    'recommend_role_id',
                    'recommend_bu_id',
                    'rec_department_id',
                'grp_select_val',
                'grp_business_unit',
                'grp_department',
                'grp_state',
                'grp_city',
            ]);
        });
        }
        
    }
};