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
        Schema::table('learning_path_steps', function (Blueprint $table) {
            //
            $table->integer('completion_count')->default(0)->after('step_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_path_steps', function (Blueprint $table) {
            //
                $table->dropColumn('completion_count');
        });
    }
};
