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
    Schema::create('attendances', function (Blueprint $table) {

        $table->id();

        $table->foreignId('attendance_session_id')
              ->constrained('attendance_sessions')
              ->onDelete('cascade');

        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');

        $table->enum('status', ['present', 'absent', 'late'])
              ->default('absent');

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('attendances');
}

};
