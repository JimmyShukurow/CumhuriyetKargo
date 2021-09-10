<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_movements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ctn');
            $table->integer('cargo_id');
            $table->integer('user_id');
            $table->integer('part_no');
            $table->string('info');
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
        Schema::dropIfExists('cargo_movements');
    }
}
