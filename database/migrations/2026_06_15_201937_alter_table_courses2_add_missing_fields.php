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
        if (!Schema::hasColumn('courses2', 'bu_id') && !Schema::hasColumn('courses2', 'dept_id')) {
            Schema::table('courses2', function (Blueprint $table) {
                //
                $table->bigInteger('bu_id')->nullable()->after('outcomes');
                $table->bigInteger('dept_id')->nullable()->after('bu_id');
                $table->foreign('bu_id')->references('id')->on('business_unit')->onDelete('set null');
                $table->foreign('dept_id')->references('id')->on('department')->onDelete('set null');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('courses2', 'bu_id') && Schema::hasColumn('courses2', 'dept_id')) {
             Schema::table('courses2', function (Blueprint $table) {
                //
                $table->dropForeign(['bu_id']);
                $table->dropForeign(['dept_id']);
                $table->dropColumn(['bu_id']);
                $table->dropColumn(['dept_id']);
        });
        }
       
    }
};
