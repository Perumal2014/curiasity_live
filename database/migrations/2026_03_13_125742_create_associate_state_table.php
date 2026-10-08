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
        Schema::create('associate_state', function (Blueprint $table) {
            $table->id();
            $table->string('state_name');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('associate_country');
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('associate_state');
    }
};
