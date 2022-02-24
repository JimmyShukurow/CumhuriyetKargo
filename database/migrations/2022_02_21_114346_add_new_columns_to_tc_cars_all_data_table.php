<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToTcCarsAllDataTable extends Migration
{
    
    public function up()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->enum('confirm',[0,-1,1])->nullable();
            $table->unsignedInteger('confirmed_user')->nullable();
            $table->timestamp('confirmed_date')->nullable();
            $table->unsignedInteger('branch_code')->nullable();
        });
    }


    public function down()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->dropColumn('confirm');
            $table->dropColumn('confirmed_user');
            $table->dropColumn('confirmed_date');
            $table->dropColumn('branch_code');
        });
    }
}
