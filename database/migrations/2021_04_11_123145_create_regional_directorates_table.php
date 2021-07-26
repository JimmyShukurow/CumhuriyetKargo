<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionalDirectoratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regional_directorates', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("city")->nullable();
            $table->string("district")->nullable();
            $table->string("neigborhood")->nullable();
            $table->text("adress")->nullable();
            $table->integer("director_id")->nullable();
            $table->integer("assitant_director_id")->nullable();
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
        Schema::dropIfExists('regional_directorates');
    }
}
