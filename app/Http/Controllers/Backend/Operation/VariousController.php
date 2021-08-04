<?php

namespace App\Http\Controllers\Backend\Operation;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\ModuleGroups;
use App\Models\TransshipmentCenters;
use App\Models\Various;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function GuzzleHttp\Promise\all;

class VariousController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['agencies'] = Various::all();
        GeneralLog('Various sayfası görüntülendi.');
        return view('backend.operation.various_cars.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        GeneralLog('Acente oluştur sayfası görüntülendi.');
        return view('backend.operation.various_cars.create', compact(['data']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'arac_marka' => 'required',
            'model' => 'required',
            'plaque' => 'required',
            'tonnage' => 'required|numeric',
            'case_type' => 'required',
            'model_year' => 'required',
            'driver_name' => 'required',
            'phone' => 'required',
            'where_car' => 'required',
        ]);


        $insert = Various::create([
            'brand' => $request->arac_marka,
            'model' => $request->model,
            'plaque' => tr_strtoupper(str_replace(' ', '', $request->plaque)),
            'tonnage' => $request->tonnage,
            'case_type' => $request->case_type,
            'model_year' => $request->model_year,
            'driver_name' => tr_strtoupper($request->driver_name),
            'phone' => $request->phone,
            'creator_id' => Auth::user()->id,
            'city' => $request->where_car,
        ]);

        if ($insert) {
            GeneralLog($request->plaque . ' plakalı muhtelif araç eklendi!');
            return back()
                ->with('success', 'İşlem başarılı, araç eklendi!');
        } else
            return back()
                ->with('error', 'İşlem başarısız, lütfen daha sonra tekrar deneyin!');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['cities'] = Cities::all();
        $data['various_car'] = Various::where('id', $id)->first();
        if ($data['various_car'] === null) return redirect(route('VariousCars.index'))->with('error', "Düzenlemek istediğiniz araç bulunamadı!");
        return view('backend.operation.various_cars.edit', compact('data'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'arac_marka' => 'required',
            'model' => 'required',
            'plaque' => 'required',
            'tonnage' => 'required',
            'case_type' => 'required',
            'model_year' => 'required',
            'driver_name' => 'required',
            'phone' => 'required',
            'where_car' => 'required',
        ]);


        $my_various_car = Various::find($id)->first();
        $my_various_car->brand = $request->arac_marka;
        $my_various_car->model = $request->model;
        $my_various_car->plaque = tr_strtoupper(str_replace(' ', '', $request->plaque));
        $my_various_car->tonnage = $request->tonnage;
        $my_various_car->case_type = $request->case_type;
        $my_various_car->phone = $request->phone;
        $my_various_car->model_year = $request->model_year;
        $my_various_car->driver_name = tr_strtoupper($request->driver_name);
        $my_various_car->city = $request->where_car;
        $my_various_car->save();


        GeneralLog('Muhtelif ' . $request->arac_marka . ' plakalı araç güncellendi!');


        return back()
            ->with('success', 'Kayıt güncellendi!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = Various::find($id);
        GeneralLog($destroy->plaque . ' plakalı muhtelif araç silindi!');


        $destroy = Various::find($id)->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function getCars()
    {
        $cars = DB::table('variouses')
            ->select(['variouses.*', 'users.name_surname'])
            ->join('users', 'variouses.creator_id', '=', 'users.id');

        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
            })
            ->addColumn('edit', 'backend.operation.various_cars.column')
            ->rawColumns(['edit'])
            ->make(true);


//        $agencies = Agencies::orderBy('created_at', 'desc')->get();
//        $agencies = DB::select('CALL proc_agency_region()');

        /*
         *
             * SELECT variouses.*, users.name_surname FROM variouses
        INNER JOIN users ON users.id = variouses.creator_id

         * */
//        $query = DB::table('')

//
//        return DataTables::of($agencies)
//            ->setRowClass(function ($agency) {
//                return 'agency-item-' . $agency->id;
//            })
//            ->setRowId(function ($agency) {
//                return 'agency-item-' . $agency->id;
//            })
////            ->addColumn('intro', 'Hi {{$name_surname}}')
//            ->addColumn('regional_directorates', function ($agency) {
//                return $agency->regional_directorates != '' ? "$agency->regional_directorates  B.M." : "";
//            })
//            ->addColumn('tc_name', function ($agency) {
//                return $agency->tc_name != '' ? "$agency->tc_name  T.M." : "";
//            })
//            ->addColumn('edit', 'backend.agencies.column')
//            ->rawColumns(['edit'])
//            ->editColumn('city', function ($agency) {
//                return $agency->city . '/' . $agency->district;
//            })
//            ->editColumn('created_at', function ($agency) {
//                return Carbon::parse($agency->created_at)->format('Y-m-d H:i:s');
//            })
//            ->make(true);
    }
}
