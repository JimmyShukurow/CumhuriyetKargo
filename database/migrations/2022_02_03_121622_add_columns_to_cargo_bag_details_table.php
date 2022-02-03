<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToCargoBagDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('cargo_bag_details', function (Blueprint $table) {
            $table->timestamp('deleted_at')->after('updated_at')->nullable();
            $table->unsignedInteger('deleted_user')->after('unloader_user_id')->nullable();
            $table->enum('deleted_from', ['Yükleme', 'İndirme'])->after('deleted_user')->nullable();
        });
    }

    public function down()
    {
        Schema::table('cargo_bag_details', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('deleted_user');
            $table->dropColumn('deleted_from');
        });
    }
}
