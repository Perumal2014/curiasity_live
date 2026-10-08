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
        if (!Schema::hasTable('user_transfer_activities')) {
            Schema::create('user_transfer_activities', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('sender_id');
                $table->unsignedBigInteger('receiver_id');
                $table->string('activity_type'); // e.g., 'transfer', 'request', 'accept', 'reject'
                $table->text('description')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('sender_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('set null');
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_transfer_activities');
    }
};
