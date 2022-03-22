<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatusDefault1InTcCars extends Migration
{

    public function up()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->boolean('status')->default(true)->change();
        });
    }


    public function down()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->boolean('status')->default(false)->change();
        });
    }
}
