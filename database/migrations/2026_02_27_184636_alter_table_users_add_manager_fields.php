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
        Schema::table('users', function (Blueprint $table) {
            $table->string('manager_email')->after('mobile_verified_at')->nullable();
            $table->string('reporting_manager_id')->after('manager_email')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        $table->dropColumn('manager_email');
        $table->dropColumn('reporting_manager_id');
    }
};
