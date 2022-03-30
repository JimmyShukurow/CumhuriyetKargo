<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->integer('cargo_id');
            $table->integer('user_id');
            $table->integer('agency_id');
            $table->string('description')->nullable();
            $table->string('receiver_name_surname')->nullable();
            $table->string('receiver_tckn_vkn')->nullable();
            $table->string('degree_of_proximity')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('transfer_reason')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('deliveries');
    }
}
