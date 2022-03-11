<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpeditionSealsTable extends Migration
{

    public function up()
    {
        Schema::create('expedition_seals', function (Blueprint $table) {
            $table->id();
            $table->integer('expedition_id');
            $table->string('serial_no');
            $table->integer('user_id');
            $table->integer('opening_user_id')->nullable();
            $table->dateTime('opened_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expedition_seals');
    }
}
