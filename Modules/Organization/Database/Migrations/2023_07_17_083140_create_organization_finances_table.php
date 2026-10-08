<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('organization_finances')){
            Schema::create('organization_finances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->double('amount')->default(0);
                $table->string('type')->comment('debit or credit');
                $table->boolean('status')->default(false);
                $table->string('description',500)->nullable();
                $table->string('gateway')->nullable();
                $table->unsignedBigInteger('course_id')->nullable();
                $table->string('data_type')->nullable();
                $table->string('payment_type')->nullable();
                $table->timestamps();
            });
        }

    }


    public function down()
    {
        Schema::dropIfExists('organization_finances');
    }
}
