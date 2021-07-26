<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currents', function (Blueprint $table) {
            $table->id();
            $table->integer('current_code');
            $table->enum('category', ['Bireysel', 'Kurumsal']);
            $table->string('name')->nullable();
            $table->string('tax_administration')->nullable();
            $table->string('vkn', 10)->nullable();
            $table->string('tckn', 11)->nullable();
            $table->string('city');
            $table->string('district');
            $table->string('adress', 355);
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('gsm');
            $table->string('gsm2')->nullable();
            $table->string('email')->nullable();
            $table->string('web_site')->nullable();
            $table->string('dispatch_city')->nullable();
            $table->string('dispatch_district')->nullable();
            $table->string('dispatch_post_code')->nullable();
            $table->string('dispatch_adress')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->integer('agency')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('discount')->nullable();
            $table->string('created_by_user_id');
            $table->dateTime('contract_start_date')->nullable();
            $table->dateTime('contract_end_date')->nullable();
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
        Schema::dropIfExists('currents');
    }
}
