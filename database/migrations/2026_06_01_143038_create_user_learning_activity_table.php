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
        Schema::create('user_learning_activity', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('course_id')->nullable();
        $table->unsignedBigInteger('lesson_id')->nullable();

        $table->string('activity_type')->nullable();

        $table->dateTime('start_time')->nullable();
        $table->dateTime('end_time')->nullable();

        $table->integer('total_seconds')->default(0);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_learning_activity');
    }
};
