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
    Schema::create('attendance_sessions', function (Blueprint $table) {

        $table->id();

        $table->unsignedInteger('course_id');

        $table->date('session_date');
        $table->time('start_time');
        $table->time('end_time');
        $table->string('type')->default('face_to_face');

        $table->timestamps();

        $table->foreign('course_id')
              ->references('id')
              ->on('courses')
              ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
