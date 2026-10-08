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
    Schema::create('audit_logs', function (Blueprint $table) {
        $table->bigIncrements('id'); // primary key
        $table->string('log_name'); // type of action
        $table->text('description'); // detailed info
        $table->bigInteger('subject_id')->nullable(); // entity affected
        $table->string('subject_type')->nullable(); // entity type
        $table->bigInteger('causer_id')->nullable(); // user performing action
        $table->string('causer_type')->nullable(); // usually 'User'
        $table->json('properties')->nullable(); // extra info
        $table->timestamps(); // created_at & updated_at
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
