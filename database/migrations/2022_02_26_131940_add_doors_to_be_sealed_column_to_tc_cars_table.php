<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoorsToBeSealedColumnToTcCarsTable extends Migration
{
    public function up()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->unsignedInteger('doors_to_be_sealed')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->dropColumn('doors_to_be_sealed');
        });
    }
}
