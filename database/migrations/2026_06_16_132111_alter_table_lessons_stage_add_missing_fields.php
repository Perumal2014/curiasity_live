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
        Schema::table('lessons_stage', function (Blueprint $table) {

            $table->string('course_type')->nullable()->after('course_id');

            $table->string('start_date')->nullable()->after('course_type');
            $table->string('start_time')->nullable()->after('start_date');

            $table->string('end_date')->nullable()->after('start_time');
            $table->string('end_time')->nullable()->after('end_date');

            $table->tinyInteger('status')->default(1)->after('end_time');
            $table->tinyInteger('bu')->default(0)->after('status');

            $table->string('mode_id')->nullable()->after('bu');
            $table->string('link')->nullable()->after('mode_id');

            $table->tinyInteger('trainer_type')->default(0)->after('link');

            $table->unsignedBigInteger('instructor_id')->nullable()->after('trainer_type');

            $table->foreign('instructor_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons_stage', function (Blueprint $table) {
            //
            $table->dropColumn('course_type');
            $table->dropColumn('start_date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_date');
            $table->dropColumn('end_time');
            $table->dropColumn('status');
            $table->dropColumn('bu');
            $table->dropColumn('mode_id');
            $table->dropColumn('link');
            $table->dropColumn('trainer_type');
            $table->dropColumn('instructor_id');
        });
    }
};
