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
        Schema::table('location_code', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('country_id')->after('status')->nullable();
            $table->foreign('country_id')->references('id')->on('associate_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('location_code', function (Blueprint $table) {
            //
            $table->dropColumn('country_id');
        });
    }
};
