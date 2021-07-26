<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransshipmentCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transshipment_centers', function (Blueprint $table) {
            $table->id();
            $table->string('tc_name');
            $table->string('phone')->nullable();
            $table->string('type');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('adress')->nullable();
            $table->integer('tc_director_id')->nullable();
            $table->integer('tc_assistant_director_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('transshipment_centers');
    }
}
