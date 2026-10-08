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
        Schema::table('users', function (Blueprint $table) {

            if (!Schema::hasColumn('users', 'dept_id')) {
                $table->foreignId('dept_id')
                    ->nullable()
                    ->constrained('department')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('users', 'profile_id')) {
                $table->foreignId('profile_id')
                    ->nullable()
                    ->constrained('profile')
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('users', 'location_code')) {
                $table->string('location_code')->nullable()->after('profile_id');
            }

            if (!Schema::hasColumn('users', 'mgt_id')) {
                $table->unsignedBigInteger('mgt_id')->nullable()->after('location_code');
            }

            if (!Schema::hasColumn('users', 'data_source')) {
                $table->string('data_source')->nullable()->after('mgt_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dept_id');
            $table->dropColumn('profile_id');
            $table->dropColumn('location_code');
            $table->dropColumn('mgt_id');
            $table->dropColumn('data_source');
        });
    }
};
