<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->integer('expedition_serial_no');
            $table->integer('car_id');
            $table->integer('user_id');
            $table->string('description');
            $table->integer('branch_code');
            $table->integer('done')->default(0)->comment('1 bitti, 0 bitmedi');
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
        Schema::dropIfExists('expeditions');
    }
}
