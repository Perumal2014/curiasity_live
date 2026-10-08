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
        Schema::create('learning_path_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('learning_path_id')->nullable();
            $table->foreign('learning_path_id')->references('id')->on('learning_paths');
            $table->integer('step_order')->default('0')->nullable();
            $table->string('step_title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_path_steps');
    }
};
