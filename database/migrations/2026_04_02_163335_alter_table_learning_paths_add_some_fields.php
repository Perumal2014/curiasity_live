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
        if (!Schema::hasColumn('learning_paths', 'category_id')) {
            Schema::table('learning_paths', function (Blueprint $table) {
            // Ensure columns have correct types
            $table->unsignedBigInteger('category_id')->nullable()->after('id');
            $table->unsignedBigInteger('sub_category_id')->nullable()->after('category_id');
            $table->unsignedBigInteger('primary_skill_id')->nullable()->after('sub_category_id');

            // Other new columns
            $table->string('thumbnail')->nullable()->after('primary_skill_id');
            $table->integer('path_level')->nullable()->after('thumbnail');
            $table->integer('duration')->nullable()->after('path_level');
            $table->integer('approve_type')->nullable()->after('duration');
            $table->tinyInteger('course_sequence')->nullable()->after('approve_type');
            $table->string('secondary_skill_id')->nullable()->after('primary_skill_id');
            $table->tinyInteger('is_recommended_for')->default(0)->after('secondary_skill_id');
            $table->tinyInteger('is_certificate')->default(0)->after('is_recommended_for');
            $table->tinyInteger('path_type')->default(1)->after('is_certificate');
            $table->tinyInteger('path_scope')->default(1)->after('path_type');
        });

        // Add foreign keys in a separate Schema::table block
        Schema::table('learning_paths', function (Blueprint $table) {
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');

            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('sub_categories')
                  ->onDelete('set null');

            $table->foreign('primary_skill_id')
                  ->references('id')
                  ->on('skills')
                  ->onDelete('set null');
        });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_paths', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['category_id']);
            $table->dropForeign(['sub_category_id']);
            $table->dropForeign(['primary_skill_id']);

            // Drop columns
            $table->dropColumn([
                'category_id',
                'sub_category_id',
                'primary_skill_id',
                'thumbnail',
                'path_level',
                'duration',
                'approve_type',
                'course_sequence',
                'secondary_skill_id',
                'is_recommended_for',
                'is_certificate',
                'path_type',
                'path_scope'
            ]);
        });
    }
};