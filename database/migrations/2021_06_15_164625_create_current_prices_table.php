<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('current_code');
            $table->double('file_price');
            $table->double('d_1_5');
            $table->double('d_6_10');
            $table->double('d_11_15');
            $table->double('d_16_20');
            $table->double('d_21_25');
            $table->double('d_26_30');
            $table->double('amount_of_increase');
            $table->double('collect_price');
            $table->integer('collect_amount_of_increase');
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
        Schema::dropIfExists('current_prices');
    }
}
