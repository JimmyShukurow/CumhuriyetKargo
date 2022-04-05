<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCargoReceiverNameToCargoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargoes', function (Blueprint $table) {
            $table->string('cargo_receiver_name')->nullable()->after('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargoes', function (Blueprint $table) {
            $table->dropColumn('cargo_receiver_name');
        });
    }
}
