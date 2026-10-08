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
        if (!Schema::hasTable('feedback_allocation')) {
            Schema::create('feedback_allocation', function (Blueprint $table) {

            $table->id();

            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('feedback_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamp('allocated_at')->nullable();

            $table->timestamps();

            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('feedback_id')
                ->references('id')
                ->on('feedbacks')
                ->onDelete('cascade');

            $table->foreign('enrollment_id')
                ->references('id')
                ->on('course_enrollds')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
        }
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_allocation');
    }
};
