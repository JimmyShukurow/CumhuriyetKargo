<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSomeColsNullableInExpeditionCargosTable extends Migration
{
    public function up()
    {
        Schema::table('expedition_cargos', function (Blueprint $table) {
            $table->integer('unloading_user_id')->nullable()->change();
            $table->dateTime('unloading_at')->nullable()->change();

        });
    }

    public function down()
    {
        Schema::table('expedition_cargos', function (Blueprint $table) {
            $table->integer('unloading_user_id')->change();
            $table->dateTime('unloading_at')->change();
        });
    }
}
