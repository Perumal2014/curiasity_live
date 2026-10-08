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
        Schema::table('learning_plan_courses', function (Blueprint $table) {
            //
            // learning_plan_milestone_id
            $table->unsignedBigInteger('learning_plan_milestone_id')->nullable();
            $table->foreign('learning_plan_milestone_id')
                        ->references('id')
                        ->on('learning_plan_milestones')
                        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_plan_courses', function (Blueprint $table) {
            //
            $table->dropColumn('learning_plan_milestone_id');
        });
    }
};
