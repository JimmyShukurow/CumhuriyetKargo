<?php

namespace App\Http\Controllers\Backend\RegionalDirectorate;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Models\RegioanalDirectorates;
use App\Models\RegionalDistricts;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RDController extends Controller
{
    public function index()
    {
        $data = DB::table('view_regional_directorates_detail')->get();
        GeneralLog('Bölge müdürlükleri sayfası görüntülendi.');
        return view('backend.regional_directorates.index', compact('data'));
    }

    public function addRd()
    {
        $data['cities'] = Cities::all();
        $data['users'] = User::all();
        GeneralLog('Bölge müdürlüğü oluştur sayfası görüntülendi.');
        return view('backend.regional_directorates.create', compact(['data']));
    }

    public function insertRd(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required|numeric',
            'district' => 'nullable|numeric',
            'neighborhood' => 'nullable|numeric',
            'director' => 'nullable|numeric',
            'assistant_director' => 'nullable|numeric'
        ]);

        $city = Cities::where('id', $request->city)->first()->city_name;
        if ($request->district !== null)
            $district = Districts::where('id', $request->district)->first()->district_name;
        else
            $district = null;
        if ($request->neighborhood !== null)
            $neighborhood = Neighborhoods::where('id', $request->neighborhood)->first()->neighborhood_name;
        else
            $neighborhood = null;

        $insert = RegioanalDirectorates::create([
            'name' => tr_strtoupper($request->name),
            'phone' => $request->phone,
            'city' => $city,
            'district' => $district,
            'neighborhood' => $neighborhood,
            'director_id' => $request->director,
            'assistant_director_id' => $request->assistant_director,
            'adress' => tr_strtoupper($request->adress)
        ]);

        if ($insert) return back()->with('success', 'Bölge müdürlüğü eklendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function editRd($id)
    {
        $area = RegioanalDirectorates::where('id', $id)->first();
        if ($area === null) return redirect(route('rd.Index'))->with('error', 'Düzenlemek istediğiniz bölge bulunamadı!');


        $data['districts'] = DB::table('view_city_district_neighborhoods')
            ->select('district_id', 'district_name')
            ->where('city_name', $area->city)
            ->distinct()
            ->get();

        $data['neighborhoods'] = DB::table('view_city_district_neighborhoods')
            ->where('city_name', $area->city)
            ->where('district_name', $area->district)
            ->get();

        $data['cities'] = Cities::all();
        $data['users'] = User::all();
        GeneralLog($id . 'id\'li Bölge müdürlüğü düzenle sayfası görüntülendi.');
        return view('backend.regional_directorates.edit', compact(['data', 'area']));
    }

    public function updateRd(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'city' => 'required|numeric',
            'district' => 'nullable|numeric',
            'neighborhood' => 'nullable|numeric',
            'director' => 'nullable|numeric',
            'assistant_director' => 'nullable|numeric'
        ]);

        $city = Cities::where('id', $request->city)->first()->city_name;
        if ($request->district !== null)
            $district = Districts::where('id', $request->district)->first()->district_name;
        else
            $district = null;
        if ($request->neighborhood !== null)
            $neighborhood = Neighborhoods::where('id', $request->neighborhood)->first()->neighborhood_name;
        else
            $neighborhood = null;

        $update = RegioanalDirectorates::find($id)
            ->update([
                'name' => tr_strtoupper($request->name),
                'phone' => $request->phone,
                'city' => $city,
                'district' => $district,
                'neighborhood' => $neighborhood,
                'director_id' => $request->director,
                'assistant_director_id' => $request->assistant_director,
                'adress' => tr_strtoupper($request->adress)
            ]);

        if ($update) return back()->with('success', 'Bölge müdürlüğü güncellendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    public function dersroyRd(Request $request)
    {
        $destroy = RegioanalDirectorates::find(intval($request->destroy_id))->delete();
        if ($destroy) return 1;

        return 0;
    }

    public function regionInfo(Request $request)
    {
        $region_id = $request->region_id;

        $data['region'] = RegioanalDirectorates::find($region_id);
        $data['employees'][0] = DB::table('view_user_role')->where('id', $data['region']->director_id)->first();
        $data['employees'][1] = DB::table('view_user_role')->where('id', $data['region']->assistant_director_id)->first();

        return response()->json($data, 200);
    }

    public function regionDistrict($id = -1)
    {
        $data['cities'] = Cities::all();
        $data['regional_directorates'] = RegioanalDirectorates::all();
        return view('backend.regional_directorates.region_district', compact(['data', 'id']));
    }

    public function regionalDistricts(Request $request)
    {
        $data = DB::select('call proc_get_districts_for_regional_districts(' . $request->region_id . ', ' . $request->city_id . ')');

        return response()->json($data, 200);
    }

    public function addRegDistrict(Request $request)
    {
        $insert = false;
        $city = Cities::where('id', $request->city_id)->first()->city_name;

        foreach ($request->district_array as $key) {

            $district = Districts::where('id', $key)->first()->district_name;

            $is_there = RegionalDistricts::where('region_id', $request->region_id)
                ->where('city', $city)
                ->where('district', $district)
                ->first();

            if ($is_there === null) {
                $insert = RegionalDistricts::create([
                    'region_id' => $request->region_id,
                    'city' => $city,
                    'district' => $district,
                    'district_id' => $key,
                ]);
            }
        }
        if ($insert) return 1;

        return 0;
    }

    public function listRegionalDistricts(Request $request)
    {
        if ($request->region_id != '') {
            $data = DB::table('view_region_name_and_districts')
                ->whereRaw('region_id = ' . $request->region_id)
                ->orderBy('created_at', 'desc');
        } else {
            $data = DB::table('view_region_name_and_districts');
        }

        return DataTables::of($data)
            ->setRowId(function ($data) {
                return 'region-district-item-' . $data->id;
            })
            ->addColumn('name', '{{$name}} BÖLGE MÜDÜRLÜĞÜ')
            ->orderColumn('name', 'name $1')
            ->addColumn('city', '{{$city}}')
            ->orderColumn('city', 'city $1')
            ->addColumn('district', '{{$district}}')
            ->orderColumn('district', 'district $1')
            ->addColumn('delete', '<a href="javascript:void(0)" class="text-danger trash" id="{{$id}}" from="region-district">Kaldır</a>')
            ->addColumn('action', 'path.to.view')
            ->rawColumns(['delete', 'action'])
            ->make(true);
    }

    public function destroyRdDistrict(Request $request)
    {
        $destroy = RegionalDistricts::find(intval($request->destroy_id))->delete();
        if ($destroy) return 1;

        return 0;
    }

    public function getReport()
    {
        $data['agency_quantity'] = Agencies::count();
        $data['region_quantity'] = RegioanalDirectorates::count();
        $data['tc_quantity'] = TransshipmentCenters::count();
        $data['total_districts'] = $districts = Districts::count();
        $data['regional_districts'] = $regional_districts = RegionalDistricts::count();
        $data['idle_districts_quantity'] = $districts - $regional_districts;
        $data['regions'] = DB::table('view_regional_directorates_detail')
            ->orderBy('district_covered_quantity', 'desc')
            ->get();

        GeneralLog('Bölgesel Rapor (Operasyonel) görüntülendi.');
        return view('backend.regional_directorates.report', compact('data'));
    }

    public function listIdleDistricts(Request $request)
    {
//        $data = DB::table('view_idle_districts')
//            ->select(['city_name', 'district_name']);

        $data = DB::select('call proc_idle_districts');

        return DataTables::of($data)
            ->addColumn('delete', '<a  class="text-danger">Boşta</a>')
            ->addColumn('action', 'path.to.view')
            ->rawColumns(['delete', 'action'])
            ->make(true);
    }

    public function listIdleAgenciesRegion(Request $request)
    {
        $data = DB::table('view_idle_agencies_region')
            ->select(['city', 'district', 'agency_name', 'name_surname']);

        return DataTables::of($data)
            ->addColumn('delete', '<a  class="text-danger font-weight-bold">Boşta</a>')
            ->editColumn('name_surname', '<a  class="text-primary font-weight-bold">{{$name_surname}}</a>')
            ->rawColumns(['delete', 'name_surname'])
            ->make(true);
    }

    public function listIdleAgenciesTc(Request $request)
    {
        $data = DB::table('view_idle_agencies_tc')
            ->select(['city', 'district', 'agency_name', 'name_surname']);

        return DataTables::of($data)
            ->addColumn('delete', '<a  class="text-danger font-weight-bold">Boşta</a>')
            ->editColumn('name_surname', '<a  class="text-primary font-weight-bold">{{$name_surname}}</a>')
            ->rawColumns(['delete', 'name_surname'])
            ->make(true);
    }

}
