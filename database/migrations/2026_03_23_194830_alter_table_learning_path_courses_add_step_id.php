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
        Schema::table('learning_path_courses', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('step_id')->after('learning_path_id')->nullable();
            $table->foreign('step_id')->references('id')->on('learning_path_steps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_path_courses', function (Blueprint $table) {
            //
            $table->dropColumn('step_id');
        });
    }
};
