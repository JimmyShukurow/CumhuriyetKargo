<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropBranchCodeColumnToExpeditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expeditions', function (Blueprint $table) {
            $table->dropColumn('branch_code');
            $table->dropColumn('expedition_serial_no');
            $table->integer('serial_no')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expeditions', function (Blueprint $table) {
            $table->integer('branch_code')->after('user_id');
            $table->integer('expedition_serial_no')->after('id');
            $table->dropColumn('serial_no');
        });
    }
}
