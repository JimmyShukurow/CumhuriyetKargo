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
//        $data['agencies'] = Various::all();
//        $data['cities'] = Cities::all();
//        $data['transshipment_centers'] = TransshipmentCenters::all();
        GeneralLog('Acente araçları sayfası görüntülendi.');
        return view('backend.operation.transfer_cars_agency.index');
    }

    public function create()
    {
        $user = Auth::user();
        GeneralLog('Aktarma aracı oluştur sayfası görüntülendi.');
        return view('backend.operation.transfer_cars_agency.create', ['branch' => $user->getAgency->agency_name, 'user' => $user->name_surname]);
    }

    public function allData(Request $request)
    {
        $marka = $request->marka;
        $model = $request->model;
        $plaka = $request->plaka;
        $soforAd = $request->soforAd;

        $cars = TcCars::with('creator', 'branch')
            ->where('car_type', 'Acente')
            ->when($marka, function ($q) use ($marka) {
                return $q->where('marka', 'like', '%' . $marka . '%');
            })
            ->when($model, function ($q) use ($model) {
                return $q->where('model', 'like', '%' . $model . '%');
            })
            ->when($plaka, function ($q) use ($plaka) {
                return $q->where('plaka', 'like', '%' . $plaka . '%');
            })
            ->when($soforAd, function ($q) use ($soforAd) {
                return $q->where('sofor_ad', 'like', '%' . $soforAd . '%');
            })
            ->get();

        $cars->each(function ($key) {
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
            ->editColumn('confirmation_status', function ($cars) {
                if ($cars->confirm == 0) return '<b class="text-primary"> Onay Bekliyor </b>';
                else if ($cars->confirm == 1) return '<b class="text-success"> Onaylandı </b>';
                else if ($cars->confirm == -1) return '<b class="text-danger"> Reddedildi </b>';
            })
            ->addColumn('details', 'backend.operation.transfer_cars_agency.column')
            ->rawColumns(['details', 'branch', 'creator', 'confirmation_status'])
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
                ->with('success', 'Acente aracı başarıyla kaydedildi!');
        else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function getAgencyTransferCar(Request $request)
    {
        $car = TcCars::with('branch', 'creator.getAgency', 'cikishAktarma', 'varishAktarma', 'confirmer')->where('id', $request->carID)
            ->first();

        return response()
            ->json([
                'cars' => $car
            ], 200);

        return $car;
    }
}
