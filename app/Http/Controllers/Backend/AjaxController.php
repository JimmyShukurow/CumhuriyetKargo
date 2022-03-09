<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{

    public function cityToDistrict(Request $request)
    {
        $data = Districts::select('id as key', 'district_name as name')
            ->where('city_id', $request->city_id)
            ->get();

        return response()->json($data, 200);
    }

    public function districtToNeighborhood(Request $request)
    {
        $data = Neighborhoods::select('id as key', 'neighborhood_name as name')
            ->where('district_id', $request->district_id)
            ->get();

        return response()->json($data, 200);
    }

    public function getAgency(Request $request)
    {
        $city = $request->city;
        $district = $request->district;

        $agency = DB::table('agencies')
            ->whereRaw($city && $city != '' ? "city='" . $city . "'" : '1 > 0')
            ->whereRaw($district ? "district='" . $district . "'" : '1 > 0')
            ->whereRaw('deleted_at is null')
            ->get();

        return $agency;
    }

    public function getCarInfo(Request $request)
    {
        if ($request->plaque == '')
            return response()
                ->json(['status' => 0, 'message' => 'Plaka alanı zorunludur!']);

        $plaque = tr_strtoupper($request->plaque);

        $car = TcCars::where('plaka', $plaque)
            ->first();

        if ($car == null)
            return response()
                ->json(['status' => 0, 'message' => 'Araç bulunamadı!']);

        $car->car_type = tr_strtoupper($car->car_type);

        return response()
            ->json(['status' => 1, 'car' => $car]);
    }

    public function getAllAgencies(){
        return Agencies::orderBy('agency_name')->get();
    }
    public function getAllTransshipmentCenters(){
        return TransshipmentCenters::orderBy('tc_name')->get();
    }

}
