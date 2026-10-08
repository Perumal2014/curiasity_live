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
        if (!Schema::hasTable('course_private')) {
            Schema::create('course_private', function (Blueprint $table) {
                $table->id();
                if (!Schema::hasColumn('course_private', 'course_id')) {
                    $table->foreignId('course_id')
                        ->nullable()
                        ->constrained('courses')
                        ->cascadeOnDelete();
                    }
                    if (!Schema::hasColumn('course_private', 'group_id')) {
                        $table->unsignedBigInteger('group_id');
                    }
                    if (!Schema::hasColumn('course_private', 'grp_type')) {
                        $table->string('grp_type')->nullable();
                    }
                
                $table->timestamps();
            });
        }
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_private');
    }
};
