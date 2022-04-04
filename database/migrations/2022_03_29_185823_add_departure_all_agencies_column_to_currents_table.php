<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDepartureAllAgenciesColumnToCurrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currents', function (Blueprint $table) {
            $table->integer('departure_all_agencies')->default(0)->after('agency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currents', function (Blueprint $table) {
            $table->dropColumn('departure_all_agencies');
        });
    }
}
