<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name_surname');
            $table->string('city');
            $table->string('district');
            $table->string('neighbourhood');
            $table->string('agency_name');
            $table->string('adress');
            $table->string('phone');
            $table->string('phone2');
            $table->integer('transshipment_center_code');
            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('agency_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agencies');
    }
}
