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
        Schema::create('learning_plan_milestones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learning_plan_id')->nullable();
            $table->foreign('learning_plan_id')->references('id')->on('learning_plans');
            $table->integer('no_of_days')->nullable();
            $table->integer('before_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_plan_milestones');
    }
};
