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
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->tinyInteger('recommended_available')->default('0')->after('secondary_skill_id');
            $table->unsignedBigInteger('recommended_for')->after('recommended_available')->nullable();
            $table->string('recommend_dept')->after('recommended_for')->nullable();
            $table->foreign('recommended_for')->references('id')->on('business_unit');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn('recommended_available');
             $table->dropColumn('recommended_for');
            $table->dropColumn('recommend_dept');
        });
    }
};
