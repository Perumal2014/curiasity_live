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
            $table->tinyInteger('vertified_status')->default('0')->after('is_active');
            $table->string('tenant_slogan')->nullable()->after('tenant_name');
            $table->string('tenant_experience')->nullable()->after('tenant_slogan');
            $table->tinyInteger('is_certified')->default('0')->after('tenant_experience');
            $table->string('address')->nullable()->after('is_certified');
            $table->integer('tenant_plan_id')->default('1')->after('address');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_list', function (Blueprint $table) {
            //
        });
    }
};
