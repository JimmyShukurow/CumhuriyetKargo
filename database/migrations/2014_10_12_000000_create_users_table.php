<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name_surname');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role_id');
            $table->string('phone')->nullable();
            $table->string('mac_adress')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->text('status_description')->nullable();
            $table->string('user_image')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
