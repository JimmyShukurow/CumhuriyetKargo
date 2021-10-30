<?php

namespace App\Http\Controllers\Backend\Operation;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\Districts;
use App\Models\LocalLocation;
use App\Models\Neighborhoods;
use App\Models\RegioanalDirectorates;
use App\Models\RegionalDistricts;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

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
                    'neighborhood_id' => $n_id,
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
			) AS agency_name FROM view_city_district_neighborhoods WHERE  city_name = '$city' AND district_name = '$district' order by neighborhood_name asc");

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

    public function locationReport(Request $request)
    {
        $districts = 4350;
        $data['regional_districts'] = $regional_districts = RegionalDistricts::count();
        $data['idle_districts_quantity'] = $districts - $regional_districts;

        $data['city_names'] = Cities::all();
        $data['agencies'] = Agencies::all();
        $data['cities'] = Cities::count();
        $data['agency_quantity'] = Agencies::count();
        $data['total_districts'] = $districts = Districts::count();
        $data['total_neighborhood'] = $neighborhoods = Neighborhoods::count();

        $data['local_location_completed_agencies'] = DB::table('local_locations')
            ->join('agencies', 'agencies.id', '=', 'local_locations.agency_code')
            ->select(['local_locations.*', 'agencies.deleted_at'])
            ->havingRaw('agencies.deleted_at is null')
            ->groupBy('agency_code')
            ->count();

        $data['local_location_not_completed_agencies'] = $data['agency_quantity'] - $data['local_location_completed_agencies'];
        $data['total_local_locations'] = DB::table('local_locations')
            ->count();

        $data['total_not_local_locations'] = $data['total_neighborhood'] - $data['total_local_locations'];

        $data['ab_locations'] = DB::table('local_locations')
            ->where('area_type', 'AB')
            ->count();
        $data['mb_locations'] = DB::table('local_locations')
            ->where('area_type', 'MB')
            ->count();

        $data['at_cities'] = DB::table('local_locations')
            ->groupBy('city')
            ->get();
        $data['at_cities'] = count($data['at_cities']);


        $data['at_out_cities'] = $data['cities'] - $data['at_cities'];

        $data['at_districts'] = DB::table('local_locations')
            ->groupBy('district')
            ->count();

        $data['at_out_districts'] = $districts - $data['at_districts'];

        $data['distributor_agencies'] = DB::table('view_most_distributor_agencies')
            ->orderByDesc('covered_neighborhoods')
            ->limit(15)
            ->get();

        GeneralLog('Lokasyon Rapor (Mahalli) görüntülendi.');
        return view('backend.operation.local_location.report', compact('data'));
    }

    public function GetTrGeneralLocations(Request $request)
    {
//        $city = $request->city;
//        $district = $request->district;
//        $agency = $request->agency;
//        $area_type = $request->area_type;
//
//        $city = $city ? Cities::find($city) : false;
//        $district = $district ? Districts::find($district) : false;
//
//        $data = DB::table('view_tr_general_local_location')
//            ->whereRaw($agency ? 'agency_id=' . $agency : ' 1 > 0')
//            ->whereRaw($city ? "city_name like '%" . $city->city_name . "%'" : ' 1 > 0')
//            ->whereRaw($district ? "district_name like '%" . $district->district_name . "%'" : ' 1 > 0')
//            ->whereRaw($area_type ? "area_type like '%" . $area_type . "%'" : ' 1 > 0');
//
//        return DataTables::of($data)
//            ->editColumn('area_type', function ($key) {
//                if ($key->area_type == 'AT-DIŞI')
//                    return '<b class="text-danger">' . $key->area_type . '</b>';
//                else if ($key->area_type == 'MB')
//                    return '<b class="text-info">Mobil Bölge</b>';
//                else if ($key->area_type == 'AB')
//                    return '<b class="text-success">Ana Bölge</b>';
//            })
//            ->editColumn('agency', function ($key) {
//                if ($key->agency_id != '') {
//                    $agency = Agencies::find($key->agency_id);
//                    return '<b class="text-success">' . $agency->city . ' - ' . $agency->agency_name . '</b>';
//                }
//            })
//            ->rawColumns(['area_type', 'agency'])
//            ->make(true);
    }

    public function getAgencyLocationStatus(Request $request)
    {
        $city = $request->city;
        $district = $request->district;
        $agency = $request->agency;
        $locationdone = $request->location_done;

        $city = $city ? Cities::find($city) : false;
        $district = $district ? Districts::find($district) : false;

        $data = DB::table('view_agency_location_counts')
//            ->whereRaw($agency ? 'agency_id=' . $agency : ' 1 > 0')
            ->whereRaw($city ? "city like '%" . $city->city_name . "%'" : ' 1 > 0')
            ->whereRaw($district ? "district like '%" . $district->district_name . "%'" : ' 1 > 0')
            ->whereRaw($locationdone == '0' ? 'location_count = 0' : ' 1 > 0')
            ->whereRaw($locationdone == '1' ? 'location_count > 0' : ' 1 > 0')
            ->whereRaw($agency ? "agency_name like '%" . $agency . "%'" : ' 1 > 0');

        return DataTables::of($data)
            ->editColumn('details', function ($data) {
                return '<button agency-id="' . $data->id . '" class="btn btn-danger location-detail btn-sm">Detay</button>';
            })
            ->rawColumns(['area_type', 'details'])
            ->make(true);
    }

    public function getAgencyLocations(Request $request)
    {
        $agency = Agencies::find($request->agency_id);

        if ($agency == null)
            return response()
                ->json(['status' => 0, 'message' => 'Acente Bulunamadı!'], 200);

        $locations = DB::table('local_locations')
            ->where('agency_code', $agency->id)
            ->get();

        return response()
            ->json([
                'status' => 1,
                'locations' => $locations,
                'agency' => '#' . $agency->agency_code . ' - ' . $agency->agency_name . ' ŞUBE'
            ], 200);
    }
}
