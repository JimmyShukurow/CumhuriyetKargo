<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHtfReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('htf_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_serial_no');
            $table->integer('cargo_id');
            $table->string('cargo_invoice_number');
            $table->bigInteger('cargo_tracking_no');
            $table->string('real_detecting_unit_type');
            $table->integer('detecting_user_id');
            $table->enum('reported_unit_type', ['Çıkış Şube', 'Çıkış TRM.', 'Varış Şube', 'Varış TRM.', 'Diğer Şube', 'Diğer TRM.']);
            $table->enum('real_reported_unit_type', ['Acente', 'Aktarma']);
            $table->integer('reported_unit_id');
            $table->text('damage_description');
            $table->string('content_detection');
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
        Schema::dropIfExists('htf_reports');
    }
}
