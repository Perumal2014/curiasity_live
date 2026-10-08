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
        Schema::table('business_unit', function (Blueprint $table) {
            //
            // Step 2: Drop column
            $table->unsignedBigInteger('profile_id')->after('status')->nullable();
            $table->foreign('profile_id')->references('id')->on('profile');
            $table->dropColumn('organization_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_unit', function (Blueprint $table) {
            //
            $table->dropColumn('profile_id');
            
        });
    }
};
