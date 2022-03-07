<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expedition_cargos', function (Blueprint $table) {
            $table->id();
            $table->integer('expedition_id');
            $table->integer('cargo_id');
            $table->integer('part_no');
            $table->integer('user_id');
            $table->integer('unloading_user_id');
            $table->dateTime('unloading_at');
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
        Schema::dropIfExists('expedition_cargos');
    }
}
