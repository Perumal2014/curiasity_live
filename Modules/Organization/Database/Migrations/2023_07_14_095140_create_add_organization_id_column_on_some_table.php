<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddOrganizationIdColumnOnSomeTable extends Migration
{

    public function up()
    {
        Schema::table('course_levels', function (Blueprint $table) {
            if (!Schema::hasColumn('course_levels', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable();
            }
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable();
            }
        });
    }


    public function down()
    {
       //
    }
}
