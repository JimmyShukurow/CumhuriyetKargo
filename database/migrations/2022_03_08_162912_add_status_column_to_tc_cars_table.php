<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToTcCarsTable extends Migration
{

    public function up()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->boolean('status')->default(false);
        });
    }


    public function down()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
