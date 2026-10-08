<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {


    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('color');
            $table->date('start');
            $table->date('end');
            $table->string('calendar_url')->nullable();
            $table->string('banner')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('calendar_for')->nullable()->default('1');
            $table->integer('course_id')->nullable();
            $table->longText('description')->nullable();
            $table->integer('status')->nullable()->default(1);
            $table->integer('event_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('lms_id')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('calendars');
    }
};
