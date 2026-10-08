<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    
    public function up()
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('lesson_id')->nullable()->after('course_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    
    public function down()
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn('lesson_id');
        });
    }
};
