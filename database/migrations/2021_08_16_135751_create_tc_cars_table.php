<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTcCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tc_cars', function (Blueprint $table) {
            $table->id();
            $table->string('marka');
            $table->string('model');
            $table->string('model_yili');
            $table->string('palaka');
            $table->string('arac_kapasitesi');
            $table->integer('tonaj');
            $table->integer('desi_kapasitesi');
            $table->string('arac_takip_sistemi');
            $table->string('hat');
            $table->integer('cikis_aktarma');
            $table->integer('varis_aktarma');
            $table->string('ugradigi_aktarmalar');
            $table->date('muayene_baslangic_tarihi');
            $table->date('muayene_bitis_tarihi');
            $table->date('trafik_sigortasi_baslangic_tarihi');
            $table->date('trafik_sigortasi_bitis_tarihi');

            $table->string('sofor_ad');
            $table->string('sofor_telefon');
            $table->string('sofor_adres');
            $table->string('arac_sahibi_ad');
            $table->string('arac_sahibi_telefon');
            $table->string('arac_sahibi_adres');
            $table->string('arac_sahibi_yakini_ad');
            $table->string('arac_sahibi_yakini_telefon');
            $table->string('arac_sahibi_yakini_adres');

            $table->float('aylik_kira_bedeli');
            $table->float('kdv_haric_hakedis');
            $table->float('bir_sefer_kira_maliyeti');
            $table->float('yakit_orani');
            $table->float('tur_km');
            $table->float('sefer_km');
            $table->float('bir_sefer_yakit_maliyeti');
            $table->float('aylik_yakit');
            $table->float('sefer_maliyeti');
            $table->float('hakedis_arti_mazot');

            $table->enum('stepne', ['0', '1']);
            $table->enum('kriko', ['0', '1']);
            $table->enum('zincir', ['0', '1']);
            $table->enum('bijon_anahtari', ['0', '1']);
            $table->enum('reflektor', ['0', '1']);
            $table->enum('yangin_tupu', ['0', '1']);
            $table->enum('ilk_yardim_cantasi', ['0', '1']);
            $table->enum('seyyar_lamba', ['0', '1']);
            $table->enum('cekme_halati', ['0', '1']);
            $table->enum('giydirme_kor_nokta_uyarisi', ['0', '1']);
            $table->enum('hata_bildirim_hatti', ['0', '1']);

            $table->enum('muayene_evragi', ['0', '1']);
            $table->enum('sigorta_belgesi', ['0', '1']);
            $table->enum('sofor_ehliyet', ['0', '1']);
            $table->enum('src_belgesi', ['0', '1']);
            $table->enum('ruhsat_ekspertiz_raporu', ['0', '1']);
            $table->enum('tasima_belgesi', ['0', '1']);
            $table->enum('sofor_adli_sicil_kaydi', ['0', '1']);
            $table->enum('arac_sahibi_sicil_kaydi', ['0', '1']);
            $table->enum('sofor_yakini_ikametgah_belgesi', ['0', '1']);

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
        Schema::dropIfExists('tc_cars');
    }
}
