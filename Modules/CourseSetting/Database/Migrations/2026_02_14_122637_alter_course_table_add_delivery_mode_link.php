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
        //
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'delivery_mode')) {
                $table->string('delivery_mode')->nullable();
            }
            if (!Schema::hasColumn('courses', 'meeting_link')) {
                $table->string('meeting_link')->nullable();
            }
            if (!Schema::hasColumn('courses', 'course_start_date')) {
                $table->date('course_start_date')->nullable();
            }
            if (!Schema::hasColumn('courses', 'course_end_date')) {
                $table->date('course_end_date')->nullable();
            }
            if (!Schema::hasColumn('courses', 'course_approve_level')) {
                $table->date('course_approve_level')->nullable();
            }
            if (!Schema::hasColumn('courses', 'course_approved_by')) {
                $table->integer('course_approved_by')->nullable();
            }
            if (!Schema::hasColumn('courses', 'feedback_available')) {
                $table->integer('feedback_available')->default('0');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
