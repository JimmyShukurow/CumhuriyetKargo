<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargoes', function (Blueprint $table) {
            $table->id();
            $table->integer('receiver_id'); # alıcı id
            $table->string('receiver_name'); # alıcı ad soyad
            $table->string('receiver_phone'); # alıcı telefon
            $table->string('receiver_adress'); #alıcı adres
            $table->integer('sender_id'); # cari - gönderici id
            $table->string('sender_name'); # gönderici ad soyad
            $table->string('sender_phone'); # gönderici telefon
            $table->string('sender_adress'); # gönderici adres
            $table->string('customer_code', 25)->nullable(); # müşteri özel kodu
            $table->enum('payment_type', ['Alıcı Ödemeli', 'Gönderici Ödemeli']); # ödeme tipi
            $table->integer('number_of_pieces'); # parça sayısı
            $table->enum('cargo_type', ['Dosya', 'Koli']); # kargo tiği
            $table->string('cargo_content', 255); # kargo içeriği
            $table->string('cargo_content_desc', 255)->nullable(); # kargo içerik açıklaması
            $table->bigInteger('tracking_no'); # kargo takip numarası
            $table->string('arrival_city', 30); # varış il
            $table->string('arrival_district', 30); # varış ilçe
            $table->integer('arrival_agency_code'); # varış acente kod
            $table->integer('arrival_tc_code'); # varış transfer merkezi kodu
            $table->string('departure_city', 30); # çıkış il
            $table->string('departure_district', 30); # çıkış ilçe
            $table->integer('departure_agency_code'); # çıkış acente kod
            $table->integer('departure_tc_code'); # çıkış transfer merkezi kodu
            $table->integer('creator_agency_code'); # oluşturan acente kod
            $table->integer('creator_user_id'); # oluşturan kullanıcı id
            $table->string('status', 100); # kargo durumu
            $table->enum('collectible', ['0', '1'])->default('0'); # tahsilatlı
            $table->double('collection_fee')->default(0); # tahsilat ücreti
            $table->enum('collection_paymenyt_type', ['Nakit', 'Kredi Kartı', '0'])->default('0'); # tahsilat ödeme tipi
            $table->double('desi'); # desi
            $table->double('desi_price'); # desi ücreti
            $table->double('kdv_price'); # kdv ücreti
            $table->double('transport_price'); # taşıma ücreti
            $table->double('service_price'); # hizmet ücreti
            $table->double('add_service_price'); # ek hizmet ücreti
            $table->double('total_price'); # toplam ücret
            $table->bigInteger('current_code')->nullable(); # cari kodu
            $table->enum('home_delivery', ['0', '1']); # adrese teslim
            $table->enum('pick_up_adress', ['0', '1']); # adresten alıp
            $table->enum('sms', ['0', '1']); # sms
            $table->enum('agency_delivery', ['0', '1']); # şubeye teslim
            $table->enum('cargo_refund', ['0', '1'])->default('0'); # kargo iade
            $table->string('reason_return')->nullable(); # iade nedeni
            $table->string('status_for_humen'); # insanlar için son durum - Teslim/İade/Devir vs..
            $table->enum('confirm', ['0', '1'])->default('1'); # onay
            $table->string('transporter', 100); # taşıyıcı
            $table->integer('delivery_code')->nullable(); # teslimat kodu
            $table->enum('progress_payment_paid', ['0', '1'])->default('0'); # hakediş ödendi
            $table->enum('collection_paid', ['0', '1'])->default('0'); # tahsilat ücreti ödendi
            $table->integer('deleted_by_user_id')->nullable(); # silen kullanıcı id
            $table->text('delete_reason'); # silinme nedeni
            $table->string('system', 20)->default('CKG-Sis'); # kullanılan sistem
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
        Schema::dropIfExists('cargoes');
    }
}
