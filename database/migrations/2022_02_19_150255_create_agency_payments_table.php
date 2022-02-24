<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_payments', function (Blueprint $table) {
            $table->id();
            $table->string('row_type', 50);
            $table->integer('app_id')->nullable();
            $table->integer('user_id');
            $table->string('description', 500);
            $table->integer('agency_id');
            $table->double('payment');
            $table->string('payment_channel');
            $table->string('paying_name_surname')->nullable();
            $table->dateTime('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency_payments');
    }
}
