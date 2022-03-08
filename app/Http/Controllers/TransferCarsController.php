<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferCarsRequest;
use App\Models\Cities;
use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use App\Models\Various;
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

        $cars = DB::table('tc_cars_all_data')
            ->whereRaw($marka ? "marka like '%" . $marka . "%'" : ' 1 > 0')
            ->whereRaw($model ? "model like '%" . $model . "%'" : ' 1 > 0')
            ->whereRaw($plaka ? "plaka like '%" . $plaka . "%'" : ' 1 > 0')
            ->whereRaw($hat ? "hat='" . $hat . "'" : ' 1 > 0')
            ->whereRaw($aracKapasitesi ? "arac_kapasitesi='" . $aracKapasitesi . "'" : ' 1 > 0')
            ->whereRaw($soforIletisim ? "sofor_telefon='" . $soforIletisim . "'" : ' 1 > 0')
            ->whereRaw($varisAktarma ? "varis_aktarma = $varisAktarma" : ' 1 > 0')
            ->whereRaw($cikisAktarma ? "cikis_aktarma = $cikisAktarma" : ' 1 > 0');


        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
            })
            ->editColumn('cikis_aktarma', function ($cars) {
                return '<b class="text-danger">' . $cars->cikis_akt . '</b>';
            })
            ->editColumn('varis_aktarma', function ($cars) {
                return '<b class="text-success">' . $cars->varis_akt . '</b>';
            })

            ->addColumn('edit', 'backend.operation.transfer_cars.column')
            ->rawColumns(['edit', 'varis_aktarma', 'cikis_aktarma'])
            ->make(true);
    }


    public function store(TransferCarsRequest $request)
    {
        $validated = $request->validated();
        $validated['creator_id'] = Auth::id();

        $create = TcCars::create($validated);

        if ($create)
            return back()
                ->with('success', 'Aktarma aracı başarıyla kaydedildi!');
        else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function getTransferCar(Request $request)
    {
        $cars = DB::table('tc_cars_all_data')
            ->where('id', $request->carID)
            ->first();
        $car_type = TcCars::find($request->carID);
        $cars->car_type = $car_type->car_type;
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


    public function update(TransferCarsRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['creator_id'] = Auth::id();

        $update = TcCars::find($id);
        $updated = $update->update($validated);

        if ($updated)
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
