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
        Schema::table('hr_departments', function (Blueprint $table) {
        $table->unsignedBigInteger('organization_id')->after('status');
        $table->index('organization_id');
        $table->foreign('organization_id')
            ->references('id')
            ->on('tenant_list')
            ->cascadeOnDelete();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('hr_departments', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');
        });
    }
};
