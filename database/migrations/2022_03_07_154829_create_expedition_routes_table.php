<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expedition_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('expedition_id');
            $table->integer('branch_code');
            $table->string('branch_type');
            $table->integer('order');
            $table->string('route_type')->comment('Çıkış Birim = 1, Varış Birim = -1, Ara Birim = 0');
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
        Schema::dropIfExists('expedition_routes');
    }
}
