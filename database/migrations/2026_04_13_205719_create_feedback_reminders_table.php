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
        if (!Schema::hasTable('feedback_reminders')) {
            Schema::create('feedback_reminders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->string('reminder_name')->nullable();
                $table->string('when_to_send')->nullable();
                $table->string('days_after_completion')->nullable();
                $table->string('recurrence')->nullable();
                $table->string('for')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_reminders');
    }
};
