<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgencyTransferCarRequest;
use App\Models\Cities;
use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use App\Models\Various;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyTransferCarsController extends Controller
{
    public function index()
    {
        $data['agencies'] = Various::all();
        $data['cities'] = Cities::all();
        $data['transshipment_centers'] = TransshipmentCenters::all();
        GeneralLog('Aktarma araçları sayfası görüntülendi.');
        return view('backend.operation.transfer_cars_agency.index', compact('data'));
    }

    public function create()
    {
        $user = Auth::user();
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        GeneralLog('Aktarma aracı oluştur sayfası görüntülendi.');
        return view('backend.operation.transfer_cars_agency.create', ['branch'=> $user->getAgency->agency_name, 'user' => $user->name_surname]);
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
            ->where('car_type', 'Acente')
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

        $cars->each(function($key){ 
            $key['cikishAktarma'] = $key->cikishAktarma->tc_name ?? null; 
            $key['varishAktarma'] = $key->varishAktarma->tc_name ?? null; 
            $muayene_date_diff = Carbon::now()->diffInDays($key->muayene_bitis_tarihi);
            $key['muayene_kalan_sure'] = Carbon::now() < Carbon::parse($key->muayene_bitis_tarihi) ? $muayene_date_diff : - $muayene_date_diff;
            $sigorta_date_diff = Carbon::now()->diffInDays($key->trafik_sigortasi_bitis_tarihi);
            $key['sigorta_kalan_sure'] = Carbon::now() < Carbon::parse($key->muayene_bitis_tarihi) ? $sigorta_date_diff : - $sigorta_date_diff;
        });
            


        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
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
            
          
            ->addColumn('edit', 'backend.operation.transfer_cars.column')
            ->rawColumns(['edit', 'varis_aktarma', 'cikis_aktarma', 'kdv_haric_hakedis', 'yakit_orani', 'bir_sefer_kira_maliyeti', 'hakedis_arti_mazot', 'aylik_yakit', 'sefer_km', 'tur_km', 'bir_sefer_yakit_maliyeti'])
            ->make(true);
    }

    public function store(AgencyTransferCarRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        $validated['branch_code'] = $user->getAgency->id;
        $validated['confirm'] = '0';
        $validated['creator_id'] = $user->id;

        $create = TcCars::create($validated);

        if ($create)
            return back()
                ->with('success', 'Aktarma aracı başarıyla kaydedildi!');
        else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }
}
