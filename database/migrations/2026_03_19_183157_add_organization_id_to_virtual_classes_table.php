<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganizationIdToVirtualClassesTable extends Migration
{
    public function up()
    {
        Schema::table('virtual_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable()->after('id')->index();
        });
    }

    public function down()
    {
        Schema::table('virtual_classes', function (Blueprint $table) {
            $table->dropColumn('organization_id');
        });
    }
}
