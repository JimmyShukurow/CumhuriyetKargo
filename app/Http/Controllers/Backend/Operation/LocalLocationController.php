<?php

namespace App\Http\Controllers\Backend\Operation;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\Districts;
use App\Models\LocalLocation;
use App\Models\Neighborhoods;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LocalLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['agencies'] = Agencies::all();
        $data['gm_users'] = DB::table('users')
            ->where('agency_code', 1)
            ->get();
        $data['cities'] = Cities::all();

        return view('backend.operation.local_location.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        return $request->all();

        $insert = false;
        $city = Cities::find($request->city_id);
        $district = Districts::find($request->district_id);
        $neighArray = $request->neighborhood_array;

        for ($i = 0, $iMax = count($neighArray); $i < $iMax; $i++) {

            $area_type = substr($neighArray[$i], 0, 2);
            $n_id = substr($neighArray[$i], 3, strlen($neighArray[$i]));

            $neighborhood = Neighborhoods::find($n_id);

            $get = DB::table('local_locations')
                ->where('city', $city->city_name)
                ->where('district', $district->district_name)
                ->where('neighborhood', $neighborhood->neighborhood_name)
                ->count();

            if ($get == 0) {
                $insert = LocalLocation::create([
                    'agency_code' => $request->agency_code,
                    'city' => $city->city_name,
                    'district' => $district->district_name,
                    'neighborhood' => $neighborhood->neighborhood_name,
                    'area_type' => $area_type,
                ]);
            }
        }

        if ($insert)
            return response()
                ->json(['status' => 1], 200);

        return response()
            ->json(['status' => 0, 'message' => 'İşlem Başarısız, Lütfen daha sonra tekrar deneyin!'], 200);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = LocalLocation::find($id)
            ->delete();

        if ($delete)
            return 1;
        else
            return 0;
    }

    public function getNeighborhoodsOfAgency(Request $request)
    {
        $district = Districts::find($request->district);
        $district = $district->district_name;

        $city = Cities::find($request->city);
        $city = $city->city_name;

        $data = DB::select("SELECT *,
        (SELECT COUNT(*) FROM local_locations WHERE city = city_name AND district = district_name AND neighborhood = neighborhood_name ) AS count,
	    (SELECT area_type FROM local_locations WHERE city = city_name AND district = district_name AND neighborhood = neighborhood_name ) AS area_type,
			(SELECT agency_code FROM local_locations WHERE city = city_name AND district = district_name AND neighborhood = neighborhood_name ) AS agency_code,
			(SELECT agencies.agency_name FROM agencies WHERE agencies.id =
            (SELECT agency_code FROM local_locations WHERE city = city_name AND district = district_name AND neighborhood = neighborhood_name )
			) AS agency_name FROM view_city_district_neighborhoods WHERE  city_name = '$city' AND district_name = '$district'");

        return $data;
    }

    public function getLocation(Request $request)
    {

        #getLocation
        if ($request->city)
            $city = Cities::find($request->city);

        if ($request->district)
            $district = Districts::find($request->district);

        $agency = $request->agency;


        $locations = DB::table('local_locations')
            ->select(['local_locations.*', 'agencies.agency_name'])
            ->join('agencies', 'agencies.id', '=', 'local_locations.agency_code')
            ->whereRaw($request->city ? "local_locations.`city`='" . $city->city_name . "'" : '1 > 0')
            ->whereRaw($request->district ? "local_locations.`district`='" . $district->district_name . "'" : '1 > 0')
            ->whereRaw($agency ? 'local_locations.agency_code=' . $agency : '1 > 0');

        return datatables()->of($locations)
            ->setRowId(function ($currents) {
                return "location-item-" . $currents->id;
            })
            ->editColumn('area_type', function ($location) {
                return $location->area_type == 'AB' ? '<b class="text-primary">Ana Bölge</b>' : '<b class="text-alternate">Mobil Bölge</b>';
            })
            ->addColumn('edit', function ($location) {
                return '<a class="text-danger font-weight-bold trash" from="location" id="' . $location->id . '" href="javascript:void(0)">Kaldır</a>';
            })
            ->rawColumns(['edit', 'area_type'])
            ->make(true);
    }


}
