<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyPaymentAppsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_payment_apps', function (Blueprint $table) {
            $table->id();
            $table->integer('agency_id');
            $table->integer('user_id');
            $table->double('paid');
            $table->double('confirm_paid')->nullable();
            $table->string('payment_channel');
            $table->string('file1')->nullable();
            $table->string('file2')->nullable();
            $table->string('file3')->nullable();
            $table->string('description', 500)->nullable();
            $table->string('currency')->default('TL');
            $table->enum('confirm', ['0', '1', '-1'])->default('0');
            $table->integer('confirming_user_id')->nullable();
            $table->dateTime('confirming_date')->nullable();
            $table->string('reject_reason')->nullable();
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
        Schema::dropIfExists('agency_payment_apps');
    }
}
