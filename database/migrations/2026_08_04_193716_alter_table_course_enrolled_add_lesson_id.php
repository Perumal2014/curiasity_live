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
        if (!Schema::hasColumn('course_enrolleds', 'lesson_id')) {
            Schema::table('course_enrolleds', function (Blueprint $table) {
                //
                $table->unsignedBigInteger('lesson_id')->nullable()->after('course_id');
                $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_enrolleds', function (Blueprint $table) {
            //
            $table->dropForeign(['lesson_id']);
            $table->dropColumn('lesson_id');
        });
    }
};
