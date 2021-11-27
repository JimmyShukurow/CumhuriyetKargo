<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUtfImproprietyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('utf_impropriety_details', function (Blueprint $table) {
            $table->id();
            $table->integer('utf_id');
            $table->integer('impropriety_id');
            $table->string('impropriety_text');
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
        Schema::dropIfExists('utf_impropriety_details');
    }
}
