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

            if (!Schema::hasColumn('feedback_reminders', 'feedback_id')) {

                $table->unsignedBigInteger('feedback_id')->nullable()->after('course_id');

                $table->foreign('feedback_id')
                    ->references('id')
                    ->on('feedbacks')
                    ->onDelete('cascade');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_reminders', function (Blueprint $table) {
            //
            $table->dropColumn('feedback_id');
        });
    }
};
