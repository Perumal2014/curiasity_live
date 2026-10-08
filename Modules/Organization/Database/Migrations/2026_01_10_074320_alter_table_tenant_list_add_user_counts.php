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
            $table->integer('allowed_user_counts')->default(0)->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('tenant_list', function (Blueprint $table) {
            $table->dropColumn('allowed_user_counts');
        });
    }
};
