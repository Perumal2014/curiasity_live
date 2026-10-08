<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScormColumnInLessonTable extends Migration
{

    public function up()
    {
        Schema::table('lessons', function ($table) {
            if (!Schema::hasColumn('lessons', 'scorm_title')) {
                $table->text('scorm_title')->nullable();
            }

            if (!Schema::hasColumn('lessons', 'scorm_version')) {
                $table->text('scorm_version')->nullable();
            }
            if (!Schema::hasColumn('lessons', 'scorm_identifier')) {
                $table->text('scorm_identifier')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
