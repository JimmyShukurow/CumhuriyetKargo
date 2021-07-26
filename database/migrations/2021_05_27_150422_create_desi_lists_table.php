<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesiListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desi_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('start_desi');
            $table->integer('finish_desi');
            $table->double('desi_price');
            $table->double('corporate_unit_price');
            $table->double('individual_unit_price');
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
        Schema::dropIfExists('desi_lists');
    }
}
