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
        Schema::table('profile', function (Blueprint $table) {

            // Step 1: Drop foreign key constraint
                $table->dropForeign(['dept_id']); // column name

                // Step 2: Drop column
                $table->dropColumn('dept_id');
            });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile', function (Blueprint $table) {
            //
            // Re-add column
            $table->unsignedBigInteger('dept_id');

            // Re-add foreign key
            $table->foreign('dept_id')->references('id')->on('department')->onDelete('cascade');
        });
    }
};
