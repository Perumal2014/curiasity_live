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
        if (!Schema::hasTable('course_recommend')) {
            Schema::create('course_recommend', function (Blueprint $table) {
                $table->id();

                $table->foreignId('course_id')
                    ->constrained('courses')
                    ->cascadeOnDelete();

                $table->integer('recommend_role_id')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_recommend');
    }
};
