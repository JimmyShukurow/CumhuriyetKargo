<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorialsTable extends Migration
{

    public function up()
    {
        Schema::create('tutorials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('category');
            $table->string('embedded_link');
            $table->string('tutor');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('tutorials');
    }
}
