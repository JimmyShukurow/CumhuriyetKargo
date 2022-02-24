<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInTcCarsTable extends Migration
{
  
    public function up()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            $table->string('marka')->nullable()->change();
            $table->string('model')->nullable()->change();
            $table->string('model_yili')->nullable()->change();
            $table->string('plaka')->nullable()->change();
            $table->string('arac_kapasitesi')->nullable()->change();
            $table->integer('tonaj')->nullable()->change();
            $table->integer('desi_kapasitesi')->nullable()->change();
            $table->string('arac_takip_sistemi')->nullable()->change();
            $table->string('hat')->nullable()->change();
            $table->integer('cikis_aktarma')->nullable()->change();
            $table->integer('varis_aktarma')->nullable()->change();
            $table->string('ugradigi_aktarmalar')->nullable()->change();
            $table->date('muayene_baslangic_tarihi')->nullable()->change();
            $table->date('muayene_bitis_tarihi')->nullable()->change();
            $table->date('trafik_sigortasi_baslangic_tarihi')->nullable()->change();
            $table->date('trafik_sigortasi_bitis_tarihi')->nullable()->change();
            $table->string('sofor_ad')->nullable()->change();
            $table->string('sofor_telefon')->nullable()->change();
            $table->string('sofor_adres')->nullable()->change();
            $table->string('arac_sahibi_ad')->nullable()->change();
            $table->string('arac_sahibi_telefon')->nullable()->change();
            $table->string('arac_sahibi_adres')->nullable()->change();
            $table->string('arac_sahibi_yakini_ad')->nullable()->change();
            $table->string('arac_sahibi_yakini_telefon')->nullable()->change();
            $table->string('arac_sahibi_yakini_adres')->nullable()->change();

        });

        DB::statement('ALTER TABLE tc_cars MODIFY  aylik_kira_bedeli DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  kdv_haric_hakedis DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  bir_sefer_kira_maliyeti DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  yakit_orani DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  tur_km DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  sefer_km DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  bir_sefer_yakit_maliyeti DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  aylik_yakit DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  sefer_maliyeti DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  hakedis_arti_mazot DOUBLE(8,2) NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  stepne ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  kriko ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  zincir ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  bijon_anahtari ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  reflektor ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  yangin_tupu ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  ilk_yardim_cantasi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  seyyar_lamba ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  cekme_halati ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  giydirme ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  kor_nokta_uyarisi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  hata_bildirim_hatti ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  muayene_evragi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  sigorta_belgesi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  sofor_ehliyet ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  src_belgesi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  ruhsat_ekspertiz_raporu ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  tasima_belgesi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  sofor_adli_sicil_kaydi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  arac_sahibi_sicil_kaydi ENUM("0","1") NULL');
        DB::statement('ALTER TABLE tc_cars MODIFY  sofor_yakini_ikametgah_belgesi ENUM("0","1") NULL');

    }

  
    public function down()
    {
        Schema::table('tc_cars', function (Blueprint $table) {
            //
        });
    }
}
