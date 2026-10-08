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
        Schema::table('tenant_list', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('tenant_email');
            $table->string('lms_id')->nullable()->after('phone');
            $table->string('added_by')->nullable()->after('lms_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('tenant_list', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('lms_id');
            $table->dropColumn('added_by');
        });
    }
};
