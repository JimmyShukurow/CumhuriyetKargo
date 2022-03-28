<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArrivalBranchTypeToCargoBagsTable extends Migration
{

    public function up()
    {
        Schema::table('cargo_bags', function (Blueprint $table) {
            $table->string('arrival_branch_model')->nullable()->after('arrival_branch_type');
        });
    }


    public function down()
    {
        Schema::table('cargo_bags', function (Blueprint $table) {
            $table->dropColumn('arrival_branch_model');
        });
    }
}
