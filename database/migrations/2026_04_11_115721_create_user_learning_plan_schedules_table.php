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
        if (!Schema::hasTable('user_learning_plan_schedules')) {
            Schema::create('user_learning_plan_schedules', function (Blueprint $table) {
                $table->id();
                $table->integer('group_id');
                $table->integer('group_type');
                $table->unsignedBigInteger('learning_plan_id');
                $table->foreign('learning_plan_id')->references('id')->on('learning_plans')->onDelete('cascade');
                $table->unsignedBigInteger('course_id');
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->date('start_date');
                $table->date('due_date')->nullable();

                $table->enum('status', ['pending', 'active', 'completed', 'overdue'])->default('pending');

                $table->boolean('reminder_before_sent')->default(false);
                $table->boolean('reminder_after_sent')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_learning_plan_schedules');
    }
};
