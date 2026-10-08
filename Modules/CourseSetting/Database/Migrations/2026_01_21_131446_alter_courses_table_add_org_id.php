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
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('id');
            $table->unsignedBigInteger('department_id')->nullable()->after('organization_id');

            $table->foreign('organization_id')
                ->references('id')
                ->on('tenant_list')
                ->onDelete('set null');

            $table->foreign('department_id')
                ->references('id')
                ->on('hr_departments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};
