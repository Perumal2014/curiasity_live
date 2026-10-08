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
        Schema::table('learning_plan_courses', function (Blueprint $table) {
            //
            $table->integer('order')->default(1)->after('course_id');
            $table->integer('start_after_days')->default(0)->after('order');
            $table->integer('due_after_days')->nullable()->after('start_after_days');
            $table->integer('reminder_before_days')->nullable()->after('due_after_days');
            $table->integer('reminder_after_days')->nullable()->after('reminder_before_days');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_plan_courses', function (Blueprint $table) {
            //
            $table->dropColumn('order');
            $table->dropColumn('start_after_days');
            $table->dropColumn('due_after_days');
            $table->dropColumn('reminder_before_days');
            $table->dropColumn('reminder_after_days');
        });
    }
};
