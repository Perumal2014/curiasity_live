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
        Schema::table('external_trainers', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('org_id')->nullable()->after('status');

            $table->foreign('org_id')
                ->references('id')
                ->on('tenant_list')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_trainers', function (Blueprint $table) {
            //
        });
    }
};
