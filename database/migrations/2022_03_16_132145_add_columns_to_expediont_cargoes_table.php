<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToExpediontCargoesTable extends Migration
{

    public function up()
    {
        Schema::table('expedition_cargos', function (Blueprint $table) {
            $table->unsignedInteger('deleted_user_id')->nullable()->after('unloading_user_id');
            $table->timestamp('deleted_at')->nullable();
        });
    }


    public function down()
    {
        Schema::table('expedition_cargos', function (Blueprint $table) {
            $table->dropColumn('deleted_user_id');
            $table->dropColumn('deleted_at');
        });
    }
}
