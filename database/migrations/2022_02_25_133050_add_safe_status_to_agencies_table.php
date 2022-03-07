<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSafeStatusToAgenciesTable extends Migration
{

    public function up()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->enum('safe_status', ['0', '1'])->default('1')->after('permission_of_create_cargo');
            $table->text('safe_status_description')->nullable()->after('safe_status');
        });
    }


    public function down()
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn('safe_status');
            $table->dropColumn('safe_status_description');
        });
    }
}
