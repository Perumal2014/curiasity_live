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
        Schema::create('transfer_resource_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_user_id');
            $table->integer('request_sender_id');
            $table->integer('request_receiver_id');
            $table->text('description');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_resource_detail');
    }
};
