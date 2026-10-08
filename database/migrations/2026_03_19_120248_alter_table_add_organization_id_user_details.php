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
            $table->unsignedBigInteger('organization_id')->after('grp_type')->nullable();
            $table->foreign('organization_id')->references('id')->on('tenant_list');
        });

        Schema::table('department', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('organization_id')->after('grp_type')->nullable();
            $table->foreign('organization_id')->references('id')->on('tenant_list');
        });

        Schema::table('associate_city', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('organization_id')->after('grp_type')->nullable();
            $table->foreign('organization_id')->references('id')->on('tenant_list');
        });

        Schema::table('location_code', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('organization_id')->after('grp_type')->nullable();
            $table->foreign('organization_id')->references('id')->on('tenant_list');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_unit', function (Blueprint $table) {
            //
           $table->dropColumn('organization_id');
        });

       

        Schema::table('department', function (Blueprint $table) {
            //
            $table->dropColumn('organization_id');
        });

        Schema::table('associate_city', function (Blueprint $table) {
            //
            $table->dropColumn('organization_id');
        });

        Schema::table('location_code', function (Blueprint $table) {
            //
            $table->dropColumn('organization_id');
        });
    }
};
