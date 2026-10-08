<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_title', 200)->nullable();
            $table->string('for_whom', 200)->nullable()->comment('instructor, student, staff, all');
            $table->string('event_location', 200)->nullable();
            $table->string('event_des', 500)->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('url')->nullable();
            $table->integer('host_type')->nullable()->default(1);
            $table->string('host')->nullable();
            $table->string('uplad_image_file', 200)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('lms_id')->default(1);
            $table->integer('updated_by')->nullable()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_events');
    }
};
