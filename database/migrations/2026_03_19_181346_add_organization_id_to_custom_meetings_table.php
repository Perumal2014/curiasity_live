<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationIdToCustomMeetingsTable extends Migration
{
    public function up()
    {
        Schema::table('custom_meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('class_id');
        });
    }

    public function down()
    {
        Schema::table('custom_meetings', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }
}