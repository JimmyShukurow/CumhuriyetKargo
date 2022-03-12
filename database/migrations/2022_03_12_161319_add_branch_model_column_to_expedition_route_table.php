<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchModelColumnToExpeditionRouteTable extends Migration
{

    public function up()
    {
        Schema::table('expedition_routes', function (Blueprint $table) {
            $table->string('branch_model')->after('branch_type')->nullable();
        });
    }


    public function down()
    {
        Schema::table('expedition_routes', function (Blueprint $table) {
            $table->dropColumn('branch_model');
        });
    }
}
