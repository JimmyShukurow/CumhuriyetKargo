<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoBagDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_bag_details', function (Blueprint $table) {
            $table->id();
            $table->integer('bag_id');
            $table->integer('cargo_id');
            $table->integer('part_no');
            $table->integer('loader_user_id');
            $table->integer('unloader_user_id')->nullable();
            $table->dateTime('unloaded_time')->nullable();
            $table->enum('is_inside', ['0', '1'])->default('1');
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
        Schema::dropIfExists('cargo_bag_details');
    }
}
