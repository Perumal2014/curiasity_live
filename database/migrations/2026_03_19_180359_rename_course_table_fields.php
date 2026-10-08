<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->tinyInteger('has_recommend')->nullable();
            $table->string('recommend_role_id')->nullable();
            $table->string('recommend_bu_id')->nullable();
            $table->string('rec_department_id')->nullable();
            $table->string('grp_select_val')->nullable();
            $table->string('grp_business_unit')->nullable();
            $table->string('grp_department')->nullable();
            $table->string('grp_state')->nullable();
            $table->string('grp_city')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn('has_recommend');
            $table->dropColumn('recommend_role_id');
            $table->dropColumn('recommend_bu_id');
            $table->dropColumn('rec_department_id');
            $table->dropColumn('grp_select_val');
            $table->dropColumn('grp_business_unit');
            $table->dropColumn('grp_department');
            $table->dropColumn('grp_state');
            $table->dropColumn('grp_city');
        });
    }
};
