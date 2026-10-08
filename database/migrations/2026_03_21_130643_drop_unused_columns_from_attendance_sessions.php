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
        $table->dropColumn([
            'instructor_id',
            'session_date',
            'start_time',
            'end_time'
        ]);
    });
}

    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('attendance_sessions', function (Blueprint $table) {
        $table->unsignedBigInteger('instructor_id')->nullable();
        $table->date('session_date')->nullable();
        $table->time('start_time')->nullable();
        $table->time('end_time')->nullable();
    });
}
};
