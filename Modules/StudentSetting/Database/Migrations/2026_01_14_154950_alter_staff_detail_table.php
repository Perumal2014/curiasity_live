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
        //
        Schema::table('staffs', function (Blueprint $table) {
            $table->string('is_head')->default('0')->after('is_carry_active')->nullable();
            $table->string('is_class_teacher')->default('0')->after('is_head')->nullable();
        });

    }
    /**
     */
    public function down(): void
    {
        //
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn('is_head');
            $table->dropColumn('is_class_teacher');
        });
    }
};
