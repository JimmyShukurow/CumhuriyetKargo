<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallCouriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_couriers', function (Blueprint $table) {
            $table->id();
            $table->string('notification_type');
            $table->string('customer_type');
            $table->string('tc_vkn');
            $table->string('name_surname_company');
            $table->string('phone');
            $table->string('gsm');
            $table->string('mail');
            $table->text('description');
            $table->string('city');
            $table->string('district');
            $table->string('neighborhood');
            $table->string('neighborhood_id');
            $table->text('address');
            $table->integer('agency_code');
            $table->enum('courier_sent', ['0', '1'])->nullable();
            $table->string('result')->nullable();
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
        Schema::dropIfExists('call_couriers');
    }
}
