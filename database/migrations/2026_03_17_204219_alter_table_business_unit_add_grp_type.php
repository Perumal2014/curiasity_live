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
            $table->integer('grp_type')->after('status')->nullable();
        });

        Schema::table('department', function (Blueprint $table) {
            //
            $table->integer('grp_type')->after('status')->nullable();
        });


        Schema::table('associate_state', function (Blueprint $table) {
            //
            $table->integer('grp_type')->after('status')->nullable();
        });

        Schema::table('location_code', function (Blueprint $table) {
            //
            $table->integer('grp_type')->after('status')->nullable();
        });

        Schema::table('associate_city', function (Blueprint $table) {
            //
            $table->integer('grp_type')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_unit', function (Blueprint $table) {
            //
            $table->dropColumn('grp_type');
        });

        Schema::table('department', function (Blueprint $table) {
            //
            $table->dropColumn('grp_type');
        });

        Schema::table('associate_state', function (Blueprint $table) {
            //
            $table->dropColumn('grp_type');
        });

        Schema::table('location_code', function (Blueprint $table) {
            //
            $table->dropColumn('grp_type');
        });

        Schema::table('associate_city', function (Blueprint $table) {
            //
            $table->dropColumn('grp_type');
        });
    }
};
