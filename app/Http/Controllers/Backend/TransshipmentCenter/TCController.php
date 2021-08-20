<?php

namespace App\Http\Controllers\Backend\TransshipmentCenter;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Models\RegioanalDirectorates;
use App\Models\RegionalDistricts;
use App\Models\TransshipmentCenterDistricts;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = TransshipmentCenters::all();
        GeneralLog('Transfer merkezleri sayfası görüntülendi.');
        return view('backend.transshipment_centers.index', compact('data'));
    }

    # Function Get Transshipment Centers
    public function getTC()
    {
        $transshipment_centers = DB::table('view_transshipment_center_info');

        return DataTables::of($transshipment_centers)
            ->setRowId(function ($tc) {
                return 'transshipment_center-item-' . $tc->id;
            })
            ->addColumn('type', '{{$type}} AKTARMA')
            ->addColumn('agency_quantity', 58)
            ->addColumn('director_name', function ($tc) {
                return $tc->director_name != '' ? $tc->director_name : 'ATANMADI';
            })
            ->addColumn('assistant_director_name', function ($tc) {
                return $tc->assistant_director_name != '' ? $tc->assistant_director_name : 'ATANMADI';
            })
            ->addColumn('tc_name', '{{$tc_name}} TRANSFER MERKEZİ')
            ->addColumn('edit', 'backend.transshipment_centers.columns.edit')
            ->rawColumns(['edit'])
            ->editColumn('city', function ($tc) {
                return $tc->city . '/' . $tc->district;
            })
            ->editColumn('created_at', function ($tc) {
                return Carbon::parse($tc->created_at)->format('Y-m-d H:i:s');
            })
            ->make(true);
    }

    public function tcInfo(Request $request)
    {
        $tc_id = $request->tc_id;

        $data['tc'] = TransshipmentCenters::find($tc_id);
        $data['employees'][0] = DB::table('view_user_role')->where('id', $data['tc']->tc_director_id)->first();
        $data['employees'][1] = DB::table('view_user_role')->where('id', $data['tc']->tc_assistant_director_id)->first();

        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['cities'] = Cities::all();
        $data['users'] = User::all();
        GeneralLog('Transfer merkezi sayfası görüntülendi.');
        return view('backend.transshipment_centers.create', compact(['data']));
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
            'tc_name' => 'required',
            'type' => 'required',
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

        $insert = TransshipmentCenters::create([
            'tc_name' => tr_strtoupper($request->tc_name),
            'type' => tr_strtoupper($request->type),
            'phone' => $request->phone,
            'city' => $city,
            'district' => $district,
            'neighborhood' => $neighborhood,
            'tc_director_id' => $request->director,
            'tc_assistant_director_id' => $request->assistant_director,
            'adress' => tr_strtoupper($request->adress)
        ]);

        if ($insert) return back()->with('success', 'Transfer merkezi eklendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
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
        $tc = TransshipmentCenters::where('id', $id)->first();

        if ($tc === null) return redirect(route('TransshipmentCenters.index'))->with('error', 'Düzenlemek istediğiniz transfer merkezi bulunamadı.');

        $data['users'] = User::all();

        $data['districts'] = DB::table('view_city_district_neighborhoods')
            ->select('district_id', 'district_name')
            ->where('city_name', $tc->city)
            ->distinct()
            ->get();

        $data['neighborhoods'] = DB::table('view_city_district_neighborhoods')
            ->where('city_name', $tc->city)
            ->where('district_name', $tc->district)
            ->get();

        $data['cities'] = Cities::all();

        GeneralLog($id . '\'li Transfer merkezi düzenleme sayfası görüntülendi.');
        return view('backend.transshipment_centers.edit', compact(['data', 'tc']));
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
            'tc_name' => 'required',
            'type' => 'required',
            'city' => 'required|numeric',
            'district' => 'nullable|numeric',
            'neighborhood' => 'nullable|numeric',
            'director' => 'nullable|numeric',
            'status' => 'required|numeric',
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

        $update = TransshipmentCenters::find($id)
            ->update([
                'tc_name' => tr_strtoupper($request->tc_name),
                'type' => tr_strtoupper($request->type),
                'phone' => $request->phone,
                'city' => $city,
                'district' => $district,
                'neighborhood' => $neighborhood,
                'tc_director_id' => $request->director,
                'status' => $request->status,
                'status_description' => $request->status == '1' ? '' : $request->status_description,
                'tc_assistant_director_id' => $request->assistant_director,
                'adress' => tr_strtoupper($request->adress)
            ]);

        if ($update) return back()->with('success', 'Transfer merkezi düzenlendi!');

        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy = TransshipmentCenters::find(intval($id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }


    public function tcDistricts($id = -1)
    {
        $data['cities'] = Cities::all();
        $data['regional_directorates'] = RegioanalDirectorates::all();
        $data['transshipment_centers'] = TransshipmentCenters::all();

        return view('backend.transshipment_centers.tc_districts', compact('data', 'id'));
    }

    public function getDistricts(Request $request, $direktive = 'all')
    {
        #call proc_get_districts_for_tc_districts(1,2)
        if ($direktive == 'all')
            $districts = DB::select('call proc_get_districts_for_tc_districts(' . $request->tc_id . ', ' . $request->city_id . ')');

//        $districts = DB::table('view_city_districts')
//                ->where('city_id', $request->city_id)
//                ->get();
        else
            $districts = Agencies::where('city', $direktive)
                ->select(['id as key', 'city', 'district', 'agency_name', 'transshipment_center_code as tc_code'])
                ->get();

        return response()->json($districts, 200);
    }

    public function addTcDistrict(Request $request)
    {
        $insert = false;
        $city = Cities::where('id', $request->city_id)->first()->city_name;

        foreach ($request->district_array as $key) {

            $district = Districts::where('id', $key)->first()->district_name;

            $is_there = TransshipmentCenterDistricts::where('tc_id', $request->tc_id)
                ->where('city', $city)
                ->where('district', $district)
                ->first();

            if ($is_there === null) {
                $insert = TransshipmentCenterDistricts::create([
                    'tc_id' => $request->tc_id,
                    'city' => $city,
                    'district' => $district
                ]);
            }
        }
        if ($insert) return 1;

        return 0;
    }

    public function giveAgency(Request $request)
    {
        global $update;
        foreach ($request->agency_array as $id) {
            $update = Agencies::find($id)
                ->update([
                    'transshipment_center_code' => $request->tc_id
                ]);
        }

        if ($update)
            return response()->json(['status' => 1], 200);
        else
            return response()->json(['status' => 0], 200);

        return $request->tc_id;
    }

    public function ListTCDistricts(Request $request)
    {
        if ($request->tc_id != '') {
            $data = DB::table('transshipment_center_districts')
                ->join('transshipment_centers', 'transshipment_center_districts.tc_id', '=', 'transshipment_centers.id')
                ->select('transshipment_center_districts.*', 'transshipment_centers.tc_name')
                ->whereRaw('tc_id =' . $request->tc_id)
                ->orderBy('updated_at', 'desc');

        } else {
            $data = DB::table('transshipment_center_districts')
                ->join('transshipment_centers', 'transshipment_center_districts.tc_id', '=', 'transshipment_centers.id')
                ->select('transshipment_center_districts.*', 'transshipment_centers.tc_name');
        }

        return DataTables::of($data)
            ->setRowId(function ($data) {
                return 'tc-agency-item-' . $data->id;
            })
            ->addColumn('tc_name', '{{$tc_name}} TRANSFER MERKEZİ')
            ->addColumn('delete', '<a href="javascript:void(0)" class="text-danger trash" id="{{$id}}" from="tc-agency">Kaldır</a>')
            ->addColumn('action', 'path.to.view')
            ->rawColumns(['delete', 'action'])
            ->make(true);
    }

    public function destroyTCDistricts(Request $request)
    {
        $destroy = TransshipmentCenterDistricts::find(intval($request->destroy_id))
            ->delete();

        if ($destroy) return 1;

        return 0;
    }
}
