<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrasferCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trasfer_cars', function (Blueprint $table) {
            $table->id();
            $table->string('arac_marka');
            $table->string('arac_model');
            $table->string('arac_yılı');
            $table->string('plaque');
            $table->string('arac_kapasitesi');
            $table->string('tonnage');
            $table->string('desi');
            $table->string('ats');
            $table->string('hat');
            $table->string('cıkıs_aktarma');
            $table->string('varıs_aktarma');
            $table->string('ugradığı_aktarma');

            $table->string('driver_name');
            $table->string('driver_phone');
            $table->string('driver_adress');
            $table->string('arac_sahibi_ad');
            $table->string('arac_sahibi_phone');
            $table->string('arac_sahibi_yakını_adı');
            $table->string('arac_sahibi_yakını_phone');
            $table->string('arac_sahibi_adress');
            $table->string('arac_sahibi_yakını_adress');



            $table->float('aylık_kira_bedeli');
            $table->float('kdv_haric_hakedis');
            $table->float('bir_sefer_kira_maliyeti');
            $table->integer('yakıt_oranı');
            $table->integer('tur_km');
            $table->integer('sefer_km');
            $table->float('bir_sefer_yakıt_maliyeti');
            $table->float('aylık_yakıt');
            $table->float('sefer_maliyeti');
            $table->float('hakedis_plus_mazot');


            $table->enum('stepne',['1','0'])->default(0);
            $table->enum('kiriko',['1','0'])->default(0);
            $table->enum('zincir',['1','0'])->default(0);
            $table->enum('bijon_anahtarı',['1','0'])->default(0);
            $table->enum('reflektör',['1','0'])->default(0);
            $table->enum('yangın_tüpü',['1','0'])->default(0);
            $table->enum('ilk_yardım_çantası',['1','0'])->default(0);
            $table->enum('seyyar_lamba',['1','0'])->default(0);
            $table->enum('çekme_halatı',['1','0'])->default(0);
            $table->enum('giydirme_kör_nokta_uarısı',['1','0'])->default(0);
            $table->enum('hata_bildirim_hattı',['1','0'])->default(0);

            $table->enum('muayne_eğrağı',['1','0'])->default(0);
            $table->enum('sigorta_belgesi',['1','0'])->default(0);
            $table->enum('şoför_ehliyeti',['1','0'])->default(0);
            $table->enum('src_belgesi',['1','0'])->default(0);
            $table->enum('ruhsat_ekpertiz_raporu',['1','0'])->default(0);
            $table->enum('taşıma_belgesi',['1','0'])->default(0);
            $table->enum('şoför_adli_sicil_kaydi',['1','0'])->default(0);
            $table->enum('arac_sahibi_sicil_kaydı',['1','0'])->default(0);
            $table->enum('şoför_yakını_ikametgah_belgesi',['1','0'])->default(0);


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
        Schema::dropIfExists('trasfer_cars');
    }
}
