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
        if (!Schema::hasColumn('courses2', 'course_type')) {
            Schema::table('courses2', function (Blueprint $table) {
            //
                $table->string('course_type')->nullable()->after('slug');

            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('courses2', 'course_type')) {
             Schema::table('courses2', function (Blueprint $table) {
            //
                $table->dropColumn(['course_type']);
            });
        }
       
    }
};
