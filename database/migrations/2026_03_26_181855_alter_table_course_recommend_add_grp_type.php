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
        Schema::table('course_recommend', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('course_recommend', 'grp_type')) {
                $table->string('grp_type')->nullable()->after('course_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_recommend', function (Blueprint $table) {
            //
            if (Schema::hasColumn('course_recommend', 'grp_type')) {
                $table->dropColumn('grp_type');
            }
        });
    }
};
