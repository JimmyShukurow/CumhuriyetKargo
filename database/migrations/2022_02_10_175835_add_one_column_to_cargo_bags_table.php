<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOneColumnToCargoBagsTable extends Migration
{
   
    public function up()
    {
        Schema::table('cargo_bags', function (Blueprint $table) {
            $table->timestamp('last_opening_date')->after('last_closer')->nullable();
        });
    }

   
    public function down()
    {
        Schema::table('cargo_bags', function (Blueprint $table) {
            $table->dropColumn('last_opening_date');
        });
    }
}
