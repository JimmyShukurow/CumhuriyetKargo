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
use Illuminate\Support\Facades\DB;

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
            $key['branch'] = $key->branch->agency_name ?? null; 
            $key['creator'] = $key->creator->name_surname ?? null; 
        });

        // This part is Yajra Datatables, you can google it to research ...
        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
            })
            ->editColumn('branch', function ($cars) {
                return '<b class="text-success">' . $cars->branch . '</b>';
            })
            ->editColumn('creator', function ($cars) {
                return '<b class="text-success">' . $cars->creator . '</b>';
            })
          
            ->addColumn('details', 'backend.operation.transfer_cars_agency.column')
            ->rawColumns(['details', 'branch', 'creator'])
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

    public function getAgencyTransferCar(Request $request)
    {
        $car = TcCars::with('branch', 'creator')->where('id', $request->carID)
            ->first();

        return response()
            ->json([
                'cars' => $car
            ], 200);

        return $car;
    }
}
