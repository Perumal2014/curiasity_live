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
        Schema::create('tenant_galleries', function (Blueprint $table) {
            $table->id();

                $table->foreignId('organization_id')
                    ->constrained('tenant_list')
                    ->cascadeOnDelete();

                $table->text('image_url')->nullable(); // URL of the image

                $table->tinyInteger('status')->nullable(); // Status: 1-5

                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_galleries');
    }
};
