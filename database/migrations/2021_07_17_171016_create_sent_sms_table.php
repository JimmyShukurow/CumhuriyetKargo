<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sent_sms', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->string('heading');
            $table->string('subject');
            $table->text('sms_content');
            $table->string('phone');
            $table->integer('length');
            $table->integer('quantity');
            $table->integer('causer_user_id')->nullable();
            $table->string('ip_address');
            $table->string('ctn')->nullable();
            $table->enum('result', ['0', '1']);
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
        Schema::dropIfExists('sent_sms');
    }
}
