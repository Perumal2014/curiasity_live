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
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('course_type')->nullable()->after('course_id');
            $table->string('start_date')->nullable()->after('course_type');
            $table->string('start_time')->nullable()->after('start_date');
            $table->string('end_date')->nullable()->after('start_time');
            $table->string('end_time')->nullable()->after('end_date');
            $table->string('status')->nullable()->after('end_time');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn('course_type');
            $table->dropColumn('start_date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_date');
            $table->dropColumn('end_time');
                $table->dropColumn('status');
        });
    }
};
