<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoCollectionsTable extends Migration
{
    public function up()
    {
        Schema::create('cargo_collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cargo_id');
            $table->string('collection_entered')->default('EVET');
            $table->integer('collection_entered_user_id');
            $table->string('collection_type_entered');
            $table->string('confirm_code', 80)->nullable();
            $table->string('card_owner_name', 120)->nullable();
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('cargo_collections');
    }


}


