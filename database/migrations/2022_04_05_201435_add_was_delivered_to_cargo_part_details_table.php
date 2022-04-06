<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWasDeliveredToCargoPartDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargo_part_details', function (Blueprint $table) {
            $table->integer('was_delivered')->default(0)->after('cubic_meter_volume');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargo_part_details', function (Blueprint $table) {
            $table->dropColumn('was_delivered');
        });
    }
}
