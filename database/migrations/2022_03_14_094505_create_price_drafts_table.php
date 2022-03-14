<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_drafts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('file');
            $table->double('mi');
            $table->double('d_1_5');
            $table->double('d_6_10');
            $table->double('d_11_15');
            $table->double('d_16_20');
            $table->double('d_21_25');
            $table->double('d_26_30');
            $table->double('amount_of_increase');
            $table->string('agency_permission');
            $table->integer('user_id');
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
        Schema::dropIfExists('price_drafts');
    }
}
