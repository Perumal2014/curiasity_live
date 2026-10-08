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
        Schema::create('student_detail', function (Blueprint $table) {
        $table->id();

        $table->unsignedBigInteger('user_id')->unique();
        $table->unsignedBigInteger('department_id')->nullable();

        $table->string('unique_code')->nullable();
        $table->string('learning_level')->nullable();

        $table->timestamps();

        $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();

        $table->foreign('department_id')
            ->references('id')
            ->on('hr_departments')
            ->nullOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_detail');
    }
};
