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
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->text('recommended_for')->nullable()->after('outcomes');
            $table->tinyInteger('has_certificate')->nullable()->after('has_badge');
            $table->string('primary_skill_id')->nullable()->after('has_certificate');
            $table->string('secondary_skill_id')->nullable()->after('primary_skill_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn('recommended_for');
            $table->dropColumn('has_certificate');
            $table->dropColumn('primary_skill_id');
            $table->dropColumn('secondary_skill_id');
        });
    }
};
