<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchsColumnToCargoBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargo_bags', function (Blueprint $table) {
            $table->integer('arrival_branch_id')->default(0)->after('creator_user_id');
            $table->string('arrival_branch_type')->default('Aktarma')->after('arrival_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargo_bags', function (Blueprint $table) {
            $table->dropColumn('arrival_branch_id');
            $table->dropColumn('arrival_branch_type');
        });
    }
}
