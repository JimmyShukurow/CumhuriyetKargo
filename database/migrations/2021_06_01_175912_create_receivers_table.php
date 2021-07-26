<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->string('vkn', 10)->nullable();
            $table->string('tckn', 11)->nullable();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('neighborhood');
            $table->string('street')->nullable();
            $table->string('street2')->nullable();
            $table->string('building_no');
            $table->string('door_no');
            $table->string('floor');
            $table->string('adress');
            $table->integer('receiver_agency_id');
            $table->string('phone')->nullable();
            $table->string('gsm');
            $table->string('email')->nullable();
            $table->enum('category', ['Bireysel', 'Kurumsal']);
            $table->integer('code');
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
        Schema::dropIfExists('receivers');
    }
}
