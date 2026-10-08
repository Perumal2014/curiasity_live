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
        Schema::create('learning_plan_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learning_plan_id')->nullable();
            $table->foreign('learning_plan_id')->references('id')->on('learning_plans');
            $table->unsignedBigInteger('group_id');
            $table->string('group_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_plan_group');
    }
};
