<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use App\Models\TrasferCars;
use App\Models\Various;
use App\Rules\StartDateFinishDate;
use App\Rules\TcArrayControl;
use App\Rules\TcControl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransferCarsController extends Controller
{
  
    public function index()
    {
        $data['agencies'] = Various::all();
        $data['cities'] = Cities::all();
        $data['transshipment_centers'] = TransshipmentCenters::all();
        GeneralLog('Aktarma araçları sayfası görüntülendi.');
        return view('backend.operation.transfer_cars.index', compact('data'));
    }


    public function create()
    {
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        GeneralLog('Aktarma aracı oluştur sayfası görüntülendi.');
        return view('backend.operation.transfer_cars.create', compact(['data']));
    }

    public function allData(Request $request)
    {
        $marka = $request->marka;
        $model = $request->model;
        $plaka = $request->plaka;
        $hat = $request->hat;
        $aracKapasitesi = $request->aracKapasitesi;
        $cikisAktarma = $request->cikisAktarma;
        $varisAktarma = $request->varisAktarma;
        $soforIletisim = $request->soforIletisim;

        $cars = TcCars::with('creator', 'cikishAktarma', 'varishAktarma')
            ->when($marka, function($q) use($marka){ return $q->where('marka', 'like', '%'.$marka.'%');})
            ->when($model, function($q) use($model){ return $q->where('model', 'like', '%'.$model.'%');})
            ->when($plaka, function($q) use($plaka){ return $q->where('plaka', 'like', '%'.$plaka.'%');})
            ->when($hat, function($q) use($hat){ return $q->where('hat', 'like', '%'.$hat.'%');})
            ->when($aracKapasitesi, function($q) use($aracKapasitesi){ return $q->where('arac_kapasitesi', 'like', '%'.$aracKapasitesi.'%');})
            ->when($soforIletisim, function($q) use($soforIletisim){ return $q->where('sofor_telefon', 'like', '%'.$soforIletisim.'%');})
            ->when($soforIletisim, function($q) use($soforIletisim){ return $q->where('arac_sahibi_yakini_telefon', 'like', '%'.$soforIletisim.'%');})
            ->when($varisAktarma, function($q) use($varisAktarma){ return $q->where('varis_aktarma', 'like', $varisAktarma);})
            ->when($cikisAktarma, function($q) use($cikisAktarma){ return $q->where('cikis_aktarma', 'like', $cikisAktarma);})
            ->get();
            // logger($cars);
        $cars->each(function($key){ 
            $key['cikishAktarma'] = $key->cikishAktarma->tc_name ?? null; 
            $key['varishAktarma'] = $key->varishAktarma->tc_name ?? null; 
            $key['name_surname'] = $key->creator->name_surname ?? null; 
            $muayene_date_diff = Carbon::now()->diffInDays($key->muayene_bitis_tarihi);
            $key['muayene_kalan_sure'] = Carbon::now() < Carbon::parse($key->muayene_bitis_tarihi) ? $muayene_date_diff : - $muayene_date_diff;
            $sigorta_date_diff = Carbon::now()->diffInDays($key->trafik_sigortasi_bitis_tarihi);
            $key['sigorta_kalan_sure'] = Carbon::now() < Carbon::parse($key->muayene_bitis_tarihi) ? $sigorta_date_diff : - $sigorta_date_diff;
        });
            


        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
            })
            ->editColumn('aylik_kira_bedeli', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->aylik_kira_bedeli) . '</b>';
            })
            ->editColumn('kdv_haric_hakedis', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->kdv_haric_hakedis) . '</b>';
            })
            ->editColumn('bir_sefer_kira_maliyeti', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->bir_sefer_kira_maliyeti) . '</b>';
            })
            ->editColumn('yakit_orani', function ($cars) {
                return '<b class="text-alternate">%' . getDotter($cars->yakit_orani) . '</b>';
            })
            ->editColumn('tur_km', function ($cars) {
                return '<b class="text-dark">' . getDotter($cars->tur_km) . '</b>';
            })
            ->editColumn('sefer_km', function ($cars) {
                return '<b class="text-dark">' . getDotter($cars->sefer_km) . '</b>';
            })
            ->editColumn('bir_sefer_yakit_maliyeti', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->bir_sefer_yakit_maliyeti) . '</b>';
            })
            ->editColumn('aylik_yakit', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->aylik_yakit) . '</b>';
            })
            ->editColumn('bir_sefer_yakit_maliyeti', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->bir_sefer_yakit_maliyeti) . '</b>';
            })
            ->editColumn('hakedis_arti_mazot', function ($cars) {
                return '<b class="text-primary">₺' . getDotter($cars->hakedis_arti_mazot) . '</b>';
            })
            ->editColumn('cikis_aktarma', function ($cars) {
                return '<b class="text-danger">' . $cars->cikishAktarma . '</b>';
            })
            ->editColumn('varis_aktarma', function ($cars) {
                return '<b class="text-success">' . $cars->varishAktarma . '</b>';
            })
            ->editColumn('muayene_kalan_sure', function ($cars) {
                if ($cars->muayene_kalan_sure > 0)
                    return '<b class="text-success">' . $cars->muayene_kalan_sure . '</b>';
                else
                    return '<b class="text-danger">' . $cars->muayene_kalan_sure . '</b>';
            })
            ->editColumn('sigorta_kalan_sure', function ($cars) {
                if ($cars->sigorta_kalan_sure > 0)
                    return '<b class="text-success">' . $cars->sigorta_kalan_sure . '</b>';
                else
                    return '<b class="text-danger">' . $cars->sigorta_kalan_sure . '</b>';
            })
            ->addColumn('edit', 'backend.operation.transfer_cars.column')
            ->rawColumns(['edit', 'aylik_kira_bedeli', 'sigorta_kalan_sure', 'muayene_kalan_sure', 'varis_aktarma', 'cikis_aktarma', 'kdv_haric_hakedis', 'yakit_orani', 'bir_sefer_kira_maliyeti', 'hakedis_arti_mazot', 'aylik_yakit', 'sefer_km', 'tur_km', 'bir_sefer_yakit_maliyeti'])
            ->make(true);
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'plaka' => 'required|unique:tc_cars',
            'marka' => 'required',
            'model' => 'required',
            'model_yili' => 'required',
            'arac_kapasitesi' => 'required',
            'tonaj' => 'required',
            'desi_kapasitesi' => 'required|numeric',
            'arac_takip_sistemi' => 'required',
            'hat' => 'required',
            'cikis_aktarma' => ['required', 'numeric', new TcControl],
            'varis_aktarma' => ['required', 'numeric', new TcControl],
            'ugradigi_aktarmalar' => ['required', new TcArrayControl],
            'muayene_baslangic_tarihi' => 'required',
            'muayene_bitis_tarihi' => ['required', new StartDateFinishDate($request->muayene_baslangic_tarihi)],
            'trafik_sigortasi_baslangic_tarihi' => 'required',
            'trafik_sigortasi_bitis_tarihi' => ['required', new StartDateFinishDate($request->trafik_sigortasi_baslangic_tarihi)],
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
            'arac_sahibi_ad' => 'required',
            'arac_sahibi_telefon' => 'required',
            'arac_sahibi_adres' => 'required',
            'arac_sahibi_yakini_ad' => 'required',
            'arac_sahibi_yakini_telefon' => 'required',
            'arac_sahibi_yakini_adres' => 'required',
            'aylik_kira_bedeli' => 'required',
            'kdv_haric_hakedis' => 'required',
            'bir_sefer_kira_maliyeti' => 'required',
            'yakit_orani' => 'required',
            'tur_km' => 'required',
            'sefer_km' => 'required',
            'bir_sefer_yakit_maliyeti' => 'required',
            'aylik_yakit' => 'required',
            'sefer_maliyeti' => 'required',
            'hakedis_arti_mazot' => 'required',
            'stepne' => 'required|in:0,1',
            'kriko' => 'required|in:0,1',
            'zincir' => 'required|in:0,1',
            'bijon_anahtari' => 'required|in:0,1',
            'reflektor' => 'required|in:0,1',
            'yangin_tupu' => 'required|in:0,1',
            'ilk_yardim_cantasi' => 'required|in:0,1',
            'seyyar_lamba' => 'required|in:0,1',
            'cekme_halati' => 'required|in:0,1',
            'giydirme' => 'required|in:0,1',
            'kor_nokta_uyarisi' => 'required|in:0,1',
            'hata_bildirim_hatti' => 'required|in:0,1',
            'muayene_evragi' => 'required|in:0,1',
            'sigorta_belgesi' => 'required|in:0,1',
            'sofor_ehliyet' => 'required|in:0,1',
            'src_belgesi' => 'required|in:0,1',
            'ruhsat_ekspertiz_raporu' => 'required|in:0,1',
            'tasima_belgesi' => 'required|in:0,1',
            'sofor_adli_sicil_kaydi' => 'required|in:0,1',
            'arac_sahibi_sicil_kaydi' => 'required|in:0,1',
            'sofor_yakini_ikametgah_belgesi' => 'required|in:0,1',
        ]);

        $create = TcCars::create([
            'plaka' => tr_strtoupper(str_replace(' ', '', $request->plaka)),
            'marka' => tr_strtoupper($request->marka),
            'model' => tr_strtoupper($request->model),
            'model_yili' => $request->model_yili,
            'arac_kapasitesi' => $request->arac_kapasitesi,
            'tonaj' => $request->tonaj,
            'desi_kapasitesi' => $request->desi_kapasitesi,
            'arac_takip_sistemi' => $request->arac_takip_sistemi,
            'hat' => $request->hat,
            'cikis_aktarma' => $request->cikis_aktarma,
            'varis_aktarma' => $request->varis_aktarma,
            'ugradigi_aktarmalar' => $request->ugradigi_aktarmalar,
            'muayene_baslangic_tarihi' => $request->muayene_baslangic_tarihi,
            'muayene_bitis_tarihi' => $request->muayene_bitis_tarihi,
            'trafik_sigortasi_baslangic_tarihi' => $request->trafik_sigortasi_baslangic_tarihi,
            'trafik_sigortasi_bitis_tarihi' => $request->trafik_sigortasi_bitis_tarihi,
            'sofor_ad' => tr_strtoupper($request->sofor_ad),
            'sofor_telefon' => tr_strtoupper($request->sofor_telefon),
            'sofor_adres' => tr_strtoupper($request->sofor_adres),
            'arac_sahibi_ad' => tr_strtoupper($request->arac_sahibi_ad),
            'arac_sahibi_telefon' => $request->arac_sahibi_telefon,
            'arac_sahibi_adres' => tr_strtoupper($request->arac_sahibi_adres),
            'arac_sahibi_yakini_ad' => tr_strtoupper($request->arac_sahibi_yakini_ad),
            'arac_sahibi_yakini_telefon' => $request->arac_sahibi_yakini_telefon,
            'arac_sahibi_yakini_adres' => tr_strtoupper($request->arac_sahibi_yakini_adres),
            'aylik_kira_bedeli' => getDoubleValue($request->aylik_kira_bedeli),
            'kdv_haric_hakedis' => getDoubleValue($request->kdv_haric_hakedis),
            'bir_sefer_kira_maliyeti' => getDoubleValue($request->bir_sefer_kira_maliyeti),
            'yakit_orani' => getDoubleValue($request->yakit_orani),
            'tur_km' => getDoubleValue($request->tur_km),
            'sefer_km' => getDoubleValue($request->sefer_km),
            'bir_sefer_yakit_maliyeti' => getDoubleValue($request->bir_sefer_yakit_maliyeti),
            'aylik_yakit' => getDoubleValue($request->aylik_yakit),
            'sefer_maliyeti' => getDoubleValue($request->sefer_maliyeti),
            'hakedis_arti_mazot' => getDoubleValue($request->hakedis_arti_mazot),
            'stepne' => $request->stepne,
            'kriko' => $request->kriko,
            'zincir' => $request->zincir,
            'bijon_anahtari' => $request->bijon_anahtari,
            'reflektor' => $request->reflektor,
            'yangin_tupu' => $request->yangin_tupu,
            'ilk_yardim_cantasi' => $request->ilk_yardim_cantasi,
            'seyyar_lamba' => $request->seyyar_lamba,
            'cekme_halati' => $request->cekme_halati,
            'giydirme' => $request->giydirme,
            'kor_nokta_uyarisi' => $request->kor_nokta_uyarisi,
            'hata_bildirim_hatti' => $request->hata_bildirim_hatti,
            'muayene_evragi' => $request->muayene_evragi,
            'sigorta_belgesi' => $request->sigorta_belgesi,
            'sofor_ehliyet' => $request->sofor_ehliyet,
            'src_belgesi' => $request->src_belgesi,
            'ruhsat_ekspertiz_raporu' => $request->ruhsat_ekspertiz_raporu,
            'tasima_belgesi' => $request->tasima_belgesi,
            'sofor_adli_sicil_kaydi' => $request->sofor_adli_sicil_kaydi,
            'arac_sahibi_sicil_kaydi' => $request->arac_sahibi_sicil_kaydi,
            'sofor_yakini_ikametgah_belgesi' => $request->sofor_yakini_ikametgah_belgesi,
            'creator_id' => Auth::id()
        ]);

        if ($create)
            return back()
                ->with('success', 'Aktarma aracı başarıyla kaydedildi!');
        else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function getTransferCar(Request $request)
    {
        $cars = TcCars::with('cikishAktarma')->where('id', $request->carID)
            ->first();

        $value = $cars->ugradigi_aktarmalar;
        $value = substr($value, 0, strlen($value) - 1);
        $array = explode(',', $value);

        $aktarmalar = "";

        foreach ($array as $key) {
            $exist = DB::table('transshipment_centers')
                ->where('id', $key)
                ->whereRaw('deleted_at is null')
                ->first();

            if ($exist != null)
                $aktarmalar .= $exist->tc_name . ", ";
        }

        return response()
            ->json([
                'aktarmalar' => $aktarmalar,
                'cars' => $cars
            ], 200);

        return $cars;
    }


    
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
//        $data['myTransferCar'] = TrasferCars::find($id);
        $car = TcCars::find($id);
        return view('backend.operation.transfer_cars.edit', compact(['data', 'car']));
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'plaka' => 'required',
            'marka' => 'required',
            'model' => 'required',
            'model_yili' => 'required',
            'arac_kapasitesi' => 'required',
            'tonaj' => 'required',
            'desi_kapasitesi' => 'required|numeric',
            'arac_takip_sistemi' => 'required',
            'hat' => 'required',
            'cikis_aktarma' => ['required', 'numeric', new TcControl],
            'varis_aktarma' => ['required', 'numeric', new TcControl],
            'ugradigi_aktarmalar' => ['required', new TcArrayControl],
            'muayene_baslangic_tarihi' => 'required',
            'muayene_bitis_tarihi' => ['required', new StartDateFinishDate($request->muayene_baslangic_tarihi)],
            'trafik_sigortasi_baslangic_tarihi' => 'required',
            'trafik_sigortasi_bitis_tarihi' => ['required', new StartDateFinishDate($request->trafik_sigortasi_baslangic_tarihi)],
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
            'arac_sahibi_ad' => 'required',
            'arac_sahibi_telefon' => 'required',
            'arac_sahibi_adres' => 'required',
            'arac_sahibi_yakini_ad' => 'required',
            'arac_sahibi_yakini_telefon' => 'required',
            'arac_sahibi_yakini_adres' => 'required',
            'aylik_kira_bedeli' => 'required',
            'kdv_haric_hakedis' => 'required',
            'bir_sefer_kira_maliyeti' => 'required',
            'yakit_orani' => 'required',
            'tur_km' => 'required',
            'sefer_km' => 'required',
            'bir_sefer_yakit_maliyeti' => 'required',
            'aylik_yakit' => 'required',
            'sefer_maliyeti' => 'required',
            'hakedis_arti_mazot' => 'required',
            'stepne' => 'required|in:0,1',
            'kriko' => 'required|in:0,1',
            'zincir' => 'required|in:0,1',
            'bijon_anahtari' => 'required|in:0,1',
            'reflektor' => 'required|in:0,1',
            'yangin_tupu' => 'required|in:0,1',
            'ilk_yardim_cantasi' => 'required|in:0,1',
            'seyyar_lamba' => 'required|in:0,1',
            'cekme_halati' => 'required|in:0,1',
            'giydirme' => 'required|in:0,1',
            'kor_nokta_uyarisi' => 'required|in:0,1',
            'hata_bildirim_hatti' => 'required|in:0,1',
            'muayene_evragi' => 'required|in:0,1',
            'sigorta_belgesi' => 'required|in:0,1',
            'sofor_ehliyet' => 'required|in:0,1',
            'src_belgesi' => 'required|in:0,1',
            'ruhsat_ekspertiz_raporu' => 'required|in:0,1',
            'tasima_belgesi' => 'required|in:0,1',
            'sofor_adli_sicil_kaydi' => 'required|in:0,1',
            'arac_sahibi_sicil_kaydi' => 'required|in:0,1',
            'sofor_yakini_ikametgah_belgesi' => 'required|in:0,1',
        ]);

        $create = TcCars::find($id)
            ->update([
                'plaka' => tr_strtoupper(str_replace(' ', '', $request->plaka)),
                'marka' => tr_strtoupper($request->marka),
                'model' => tr_strtoupper($request->model),
                'model_yili' => $request->model_yili,
                'arac_kapasitesi' => $request->arac_kapasitesi,
                'tonaj' => $request->tonaj,
                'desi_kapasitesi' => $request->desi_kapasitesi,
                'arac_takip_sistemi' => $request->arac_takip_sistemi,
                'hat' => $request->hat,
                'cikis_aktarma' => $request->cikis_aktarma,
                'varis_aktarma' => $request->varis_aktarma,
                'ugradigi_aktarmalar' => $request->ugradigi_aktarmalar,
                'muayene_baslangic_tarihi' => $request->muayene_baslangic_tarihi,
                'muayene_bitis_tarihi' => $request->muayene_bitis_tarihi,
                'trafik_sigortasi_baslangic_tarihi' => $request->trafik_sigortasi_baslangic_tarihi,
                'trafik_sigortasi_bitis_tarihi' => $request->trafik_sigortasi_bitis_tarihi,
                'sofor_ad' => tr_strtoupper($request->sofor_ad),
                'sofor_telefon' => tr_strtoupper($request->sofor_telefon),
                'sofor_adres' => tr_strtoupper($request->sofor_adres),
                'arac_sahibi_ad' => tr_strtoupper($request->arac_sahibi_ad),
                'arac_sahibi_telefon' => $request->arac_sahibi_telefon,
                'arac_sahibi_adres' => tr_strtoupper($request->arac_sahibi_adres),
                'arac_sahibi_yakini_ad' => tr_strtoupper($request->arac_sahibi_yakini_ad),
                'arac_sahibi_yakini_telefon' => $request->arac_sahibi_yakini_telefon,
                'arac_sahibi_yakini_adres' => tr_strtoupper($request->arac_sahibi_yakini_adres),
                'aylik_kira_bedeli' => getDoubleValue($request->aylik_kira_bedeli),
                'kdv_haric_hakedis' => getDoubleValue($request->kdv_haric_hakedis),
                'bir_sefer_kira_maliyeti' => getDoubleValue($request->bir_sefer_kira_maliyeti),
                'yakit_orani' => getDoubleValue($request->yakit_orani),
                'tur_km' => getDoubleValue($request->tur_km),
                'sefer_km' => getDoubleValue($request->sefer_km),
                'bir_sefer_yakit_maliyeti' => getDoubleValue($request->bir_sefer_yakit_maliyeti),
                'aylik_yakit' => getDoubleValue($request->aylik_yakit),
                'sefer_maliyeti' => getDoubleValue($request->sefer_maliyeti),
                'hakedis_arti_mazot' => getDoubleValue($request->hakedis_arti_mazot),
                'stepne' => $request->stepne,
                'kriko' => $request->kriko,
                'zincir' => $request->zincir,
                'bijon_anahtari' => $request->bijon_anahtari,
                'reflektor' => $request->reflektor,
                'yangin_tupu' => $request->yangin_tupu,
                'ilk_yardim_cantasi' => $request->ilk_yardim_cantasi,
                'seyyar_lamba' => $request->seyyar_lamba,
                'cekme_halati' => $request->cekme_halati,
                'giydirme' => $request->giydirme,
                'kor_nokta_uyarisi' => $request->kor_nokta_uyarisi,
                'hata_bildirim_hatti' => $request->hata_bildirim_hatti,
                'muayene_evragi' => $request->muayene_evragi,
                'sigorta_belgesi' => $request->sigorta_belgesi,
                'sofor_ehliyet' => $request->sofor_ehliyet,
                'src_belgesi' => $request->src_belgesi,
                'ruhsat_ekspertiz_raporu' => $request->ruhsat_ekspertiz_raporu,
                'tasima_belgesi' => $request->tasima_belgesi,
                'sofor_adli_sicil_kaydi' => $request->sofor_adli_sicil_kaydi,
                'arac_sahibi_sicil_kaydi' => $request->arac_sahibi_sicil_kaydi,
                'sofor_yakini_ikametgah_belgesi' => $request->sofor_yakini_ikametgah_belgesi,
                'creator_id' => Auth::id()
            ]);

        if ($create)
            return back()
                ->with('success', 'Araç başarıyla güncellendi!');
        else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

  
    public function destroy($id)
    {
        $destroy = TcCars::find($id);
        GeneralLog($destroy->plaka . ' plakalı aktarma araç silindi!');

        $destroy = TcCars::find($id)->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }
}
