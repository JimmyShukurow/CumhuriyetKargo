<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectionEnteredAndCollectionTypeEnteredToCargoesTable extends Migration
{

    public function up()
    {
        Schema::table('cargoes', function (Blueprint $table) {
            $table->string('collection_entered')->default('HAYIR')->after('delivery_date');
            $table->string('collection_type_entered')->nullable()->after('collection_entered');
        });
    }


    public function down()
    {
        Schema::table('cargoes', function (Blueprint $table) {
            $table->dropColumn('collection_entered');
            $table->dropColumn('collection_type_entered');
        });
    }
}
