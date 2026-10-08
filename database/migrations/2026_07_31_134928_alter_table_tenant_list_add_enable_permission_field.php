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
        Schema::table('tenant_list', function (Blueprint $table) {
            //
            $table->tinyInteger('public_student_reg')->default('0');
            $table->tinyInteger('public_teacher_reg')->default('0');
            $table->string('brouchure', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_list', function (Blueprint $table) {
            //
            $table->dropColumn(['public_student_reg', 'public_teacher_reg']);
        });
    }
};
