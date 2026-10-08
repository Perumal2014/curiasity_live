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
        Schema::table('student_detail', function (Blueprint $table) {
            $table->string('student_roll_no')->nullable()->after('unique_code');
            $table->string('parent_no')->nullable()->after('learning_level');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('student_detail', function (Blueprint $table) {
            $table->dropColumn('student_roll_no');
            $table->dropColumn('parent_no');
        });
    }
};
