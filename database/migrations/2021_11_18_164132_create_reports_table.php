<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['HTF', 'UTF']);
            $table->string('report_serial_no');
            $table->integer('car_id')->nullable();
            $table->integer('cargo_id')->nullable();
            $table->string('cargo_invoice_number')->nullable();
            $table->bigInteger('cargo_tracking_no')->nullable();
            $table->string('real_detecting_unit_type');
            $table->integer('detecting_user_id');
            $table->enum('reported_unit_type', ['Çıkış Şube', 'Çıkış TRM.', 'Varış Şube', 'Varış TRM.', 'Diğer Şube', 'Diğer TRM.']);
            $table->enum('real_reported_unit_type', ['Acente', 'Aktarma']);
            $table->integer('reported_unit_id');
            $table->text('damage_description')->nullable();
            $table->text('content_detection')->nullable();
            $table->text('impropriety_description')->nullable();
            $table->enum('confirm', ['0', '1', '-1'])->nullable();
            $table->text('reject_reason')->nullable();
            $table->integer('confirming_user_id')->nullable();
            $table->dateTime('confirming_datetime')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
