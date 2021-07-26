<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoPartDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_part_details', function (Blueprint $table) {
            $table->id();
            $table->integer('cargo_id');
            $table->bigInteger('tracking_no');
            $table->integer('part_no');
            $table->integer('width');
            $table->integer('size');
            $table->integer('height');
            $table->integer('weight');
            $table->double('desi');
            $table->double('cubic_meter_volume');
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
        Schema::dropIfExists('cargo_part_details');
    }
}
