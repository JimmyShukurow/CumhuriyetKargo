<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TcCars extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $guarded = [];

//    protected $casts = [
//        'created_at' => 'datetime:Y-m-d H:m:s',
//    ];

    protected static $logAttributes = [
        'plaka',
        'marka',
        'model',
        'model_yili',
        'arac_kapasitesi',
        'tonaj',
        'desi_kapasitesi',
        'arac_takip_sistemi',
        'hat',
        'cikis_aktarma',
        'varis_aktarma',
        'ugradigi_aktarmalar',
        'muayene_baslangic_tarihi',
        'muayene_bitis_tarihi',
        'trafik_sigortasi_baslangic_tarihi',
        'trafik_sigortasi_bitis_tarihi',
        'sofor_ad',
        'sofor_telefon',
        'sofor_adres',
        'arac_sahibi_ad',
        'arac_sahibi_telefon',
        'arac_sahibi_adres',
        'arac_sahibi_yakini_ad',
        'arac_sahibi_yakini_telefon',
        'arac_sahibi_yakini_adres',
        'aylik_kira_bedeli',
        'kdv_haric_hakedis',
        'bir_sefer_kira_maliyeti',
        'yakit_orani',
        'tur_km',
        'sefer_km',
        'bir_sefer_yakit_maliyeti',
        'aylik_yakit',
        'sefer_maliyeti',
        'hakedis_arti_mazot',
        'stepne',
        'kriko',
        'zincir',
        'bijon_anahtari',
        'reflektor',
        'yangin_tupu',
        'ilk_yardim_cantasi',
        'seyyar_lamba',
        'cekme_halati',
        'giydirme',
        'kor_nokta_uyarisi',
        'hata_bildirim_hatti',
        'muayene_evragi',
        'sigorta_belgesi',
        'sofor_ehliyet',
        'src_belgesi',
        'ruhsat_ekspertiz_raporu',
        'tasima_belgesi',
        'sofor_adli_sicil_kaydi',
        'arac_sahibi_sicil_kaydi',
        'sofor_yakini_ikametgah_belgesi',
        'creator_id',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Aktarma arac?? $eventName.";
    }

    public function cikishAktarma()
    {
        return $this->hasOne(TransshipmentCenters::class, 'id', 'cikis_aktarma');
    }

    public function varishAktarma()
    {
        return $this->hasOne(TransshipmentCenters::class, 'id', 'varis_aktarma');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

    public function branch()
    {
        return $this->hasOne(Agencies::class, 'id', 'branch_code');
    }

    public function confirmer()
    {
        return $this->hasOne(User::class, 'id', 'confirmed_user');
    }

    public function transshipment()
    {
        return $this->hasOne(TransshipmentCenters::class, 'id', 'branch_code');
    }
}
