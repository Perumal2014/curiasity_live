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
            $table->string('tenant_banner')->nullable()->after('tenant_logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_list', function (Blueprint $table) {
            //
            $table->dropColumn('tenant_banner');
        });
    }
};
