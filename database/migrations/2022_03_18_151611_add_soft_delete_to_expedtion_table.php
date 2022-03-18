<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToExpedtionTable extends Migration
{

    public function up()
    {
        Schema::table('expeditions', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
        });
    }


    public function down()
    {
        Schema::table('expeditions', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
