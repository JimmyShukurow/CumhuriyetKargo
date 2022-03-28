<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpeditionRouteIdToExpeditionCargoesTable extends Migration
{

    public function up()
    {
        Schema::table('expedition_cargos', function (Blueprint $table) {
            $table->unsignedInteger('expedition_route_id')->nullable()->after('user_id');
        });
    }


    public function down()
    {
        Schema::table('expedition_cargos', function (Blueprint $table) {
            $table->dropColumn('expedition_route_id');
        });
    }
}
