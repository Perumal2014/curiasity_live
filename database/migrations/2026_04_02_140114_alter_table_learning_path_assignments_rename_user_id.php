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
        Schema::table('learning_path_assignments', function (Blueprint $table) {
            //
            $table->renameColumn('user_id', 'group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_path_assignments', function (Blueprint $table) {
            //
            $table->renameColumn('group_id', 'user_id');
        });
    }
};
