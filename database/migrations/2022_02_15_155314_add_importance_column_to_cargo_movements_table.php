<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportanceColumnToCargoMovementsTable extends Migration
{
   
    public function up()
    {
        Schema::table('cargo_movements', function (Blueprint $table) {
            $table->unsignedInteger('importance')->after('status')->nullable();
        });
    }

   
    public function down()
    {
        Schema::table('cargo_movements', function (Blueprint $table) {
            $table->dropColumn('importance');
        });
    }
}
