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
        Schema::create('tenant_list', function (Blueprint $table) {

            $table->id();
            $table->string('tenant_name');
            $table->string('tenant_email')->unique();
            $table->string('tenant_slug')->unique();
            $table->string('tenant_logo')->nullable();

            // Tenant Type
            $table->foreignId('tenant_type')
                ->nullable()
                ->constrained('tenant_types')
                ->nullOnDelete();

            // Created / Updated By
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_list');
    }
};
