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
        Schema::table('course_enrolleds', function (Blueprint $table) {
            //
            $table->boolean('is_learning_plan_course')->default(0)->after('is_learning_path_course');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_enrolleds', function (Blueprint $table) {
            //
            $table->dropColumn('is_learning_plan_course');
        });
    }
};
