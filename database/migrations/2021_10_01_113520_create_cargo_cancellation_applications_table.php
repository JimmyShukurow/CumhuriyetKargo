<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoCancellationApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_cancellation_applications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cargo_id');
            $table->integer('user_id');
            $table->text('application_reason');
            $table->enum('confirm', ['0', '1'])->default('0');
            $table->integer('confirming_user')->nullable();
            $table->date('approval_at')->nullable();
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
        Schema::dropIfExists('cargo_cancellation_applications');
    }
}
