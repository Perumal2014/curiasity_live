<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('attendance_sessions', function (Blueprint $table) {
        $table->string('qr_token')->nullable()->after('type');
        $table->timestamp('qr_expires_at')->nullable()->after('qr_token');
    });
}


    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('attendance_sessions', function (Blueprint $table) {
        $table->dropColumn(['qr_token', 'qr_expires_at']);
    });
}
};
