<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_bags', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Çuval', 'Torba']);
            $table->bigInteger('tracking_no');
            $table->integer('creator_user_id');
            $table->enum('status', ['0', '1']);
            $table->integer('last_opener')->nullable();
            $table->integer('last_closer')->nullable();
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
        Schema::dropIfExists('cargo_bags');
    }
}
