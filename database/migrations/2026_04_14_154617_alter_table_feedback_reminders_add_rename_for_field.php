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
        Schema::table('feedback_reminders', function (Blueprint $table) {
            //
            $table->renameColumn('for', 'for_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_reminders', function (Blueprint $table) {
            //
            $table->renameColumn('for_days', 'for');
        });
    }
};
