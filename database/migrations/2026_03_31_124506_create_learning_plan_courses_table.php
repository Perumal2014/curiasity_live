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
            if (!Schema::hasTable('learning_plan_courses')) {
                Schema::create('learning_plan_courses', function (Blueprint $table) {
                    $table->id();

                    $table->unsignedBigInteger('learning_plan_id')->nullable();
                    $table->unsignedBigInteger('course_id')->nullable();

                    $table->foreign('learning_plan_id')
                        ->references('id')
                        ->on('learning_plans')
                        ->onDelete('cascade');

                    $table->foreign('course_id')
                        ->references('id')
                        ->on('courses')
                        ->onDelete('cascade');

                    $table->timestamps();
                });
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_plan_courses');
    }
};
