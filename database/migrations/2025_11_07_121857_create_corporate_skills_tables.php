<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // ✅ Skills Table
        Schema::create('corporate_skills', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->string('category')->nullable();
            $t->boolean('status')->default(true);
            $t->timestamps();
        });

        // ✅ Job Role to Skill requirements
        Schema::create('corporate_role_skill_requirements', function (Blueprint $t) {
            $t->id();
            $t->integer('role_id')->nullable(); // linking to role from organization module
            $t->foreignId('skill_id')->constrained('corporate_skills')->cascadeOnDelete();
            $t->enum('required_level', ['Beginner', 'Intermediate', 'Expert'])->default('Beginner');
            $t->timestamps();
        });

        // ✅ User to Skill mapping
        Schema::create('corporate_user_skills', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('skill_id')->constrained('corporate_skills')->cascadeOnDelete();
            $t->enum('level', ['Beginner', 'Intermediate', 'Expert'])->default('Beginner');
            $t->timestamps();
            $t->unique(['user_id', 'skill_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('corporate_user_skills');
        Schema::dropIfExists('corporate_role_skill_requirements');
        Schema::dropIfExists('corporate_skills');
    }
};
