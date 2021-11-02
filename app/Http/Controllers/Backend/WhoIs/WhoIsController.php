<?php

namespace App\Http\Controllers\Backend\WhoIs;

use App\DataTables\AgenciesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Cities;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use App\Models\Roles;
use Yajra\DataTables\DataTables;

class WhoIsController extends Controller
{
    public function index()
    {
        $data['users'] = DB::table('view_users_general_info')->get();
        $data['agencies'] = Agencies::all();
        $data['tc'] = TransshipmentCenters::all();
        $data['roles'] = Roles::all();

        GeneralLog("'Kim Kimdir?' sayfası görüntülendi.'");

        return view('backend.who_is_who.index', compact('data'));
    }

    public function getUsers(Request $request)
    {

        if (($request->name_surname && $request->name_surname != '') || ($request->agency && $request->agency != '')
            || ($request->tc && $request->tc != '') || ($request->role && $request->role != '')
            || ($request->user_type && $request->user_type != '')
        ) {
            $users = DB::table('view_users_all_info')
                ->select([
                    'id', 'name_surname', 'display_name', 'email', 'phone', 'branch_city', 'branch_district', 'branch_name', 'user_type'
                ])
                ->whereRaw($request->filled('agency') ? 'agency_code=' . $request->agency : '1 > 0')
                ->whereRaw($request->filled('tc') ? 'tc_code=' . $request->tc : '1 > 0')
                ->whereRaw($request->filled('role') ? 'role_id=' . $request->role : '1 > 0')
                ->whereRaw($request->filled('user_type') ? "user_type='" . $request->user_type . "'" : '1 > 0')
                ->whereRaw($request->filled('name_surname') ? "name_surname like '%" . $request->name_surname . "%'" : '1 > 0');
        } else {
            $users = DB::table('view_users_all_info')
                ->select([
                    'id', 'name_surname', 'display_name', 'email', 'phone', 'branch_city', 'branch_district', 'branch_name', 'user_type'
                ]);
        }
        return datatables()->of($users)
            ->setRowId(function ($user) {
                return "user-item-" . $user->id;
            })
            ->addColumn('detail', 'backend.who_is_who.columns.edit')
            ->rawColumns(['detail'])
            ->make(true);
    }


    public function userInfo(Request $request)
    {
        $data['user'] = DB::table('view_users_all_info')
            ->where('id', $request->user)
            ->first();

        if ($data['user']->user_type == 'Acente')
            $data['director'] = DB::table('view_users_all_info')
                ->select(['view_users_all_info.name_surname', 'view_users_all_info.display_name', 'agencies.phone'])
                ->join('agencies', 'agencies.id', '=', 'view_users_all_info.id')
                ->where('view_users_all_info.agency_code', $data['user']->agency_code)
                ->first();

        else if ($data['user']->user_type == 'Aktarma')
            $data['director'] = DB::table('transshipment_centers')
                ->select(['transshipment_centers.*', 'view_users_all_info.name_surname', 'view_users_all_info.display_name', 'view_users_all_info.phone'])
                ->join('view_users_all_info', 'view_users_all_info.id', '=', 'transshipment_centers.tc_director_id')
                ->where('transshipment_centers.id', $data['user']->tc_code)
                ->first();

        $data['region'] = DB::table('regional_directorates')
            ->select(['regional_directorates.name'])
            ->join('regional_districts', 'regional_districts.region_id', '=', 'regional_directorates.id')
            ->where('regional_districts.city', $data['user']->branch_city)
            ->where('regional_districts.district', $data['user']->branch_district)
            ->first();


        $data['user_log'] = DB::table('activity_log')
            ->where('causer_id', $request->user)
            ->limit(30)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($data, 200);
    }


    public function index_agencies(AgenciesDataTable $dataTable)
    {
        $data['agencies'] = Agencies::all();
        $data['tc'] = TransshipmentCenters::all();
        $data['roles'] = Roles::all();
        $data['cities'] = Cities::all();
        GeneralLog("'Kim Kimdir?' modülünde 'Acenteler' sayfası görüntülendi.");
        return view('backend.who_is_who.agencies', compact('data'));
    }


    public function getAgencies(Request $request)
    {

        $city = $request->city == 'Seçiniz' ? false : $request->city;
        $district = $request->district == 'İlçe Seçin' ? false : $request->district;
        $tc = $request->dependency_tc == 'Seçiniz' ? false : $request->dependency_tc;

        //   $agencies = Agencies::orderBy('created_at', 'desc')->get();

        $agencies = DB::table('view_agency_region')
            ->whereRaw($request->filled('agency_code') ? 'agency_code=' . $request->agency_code : '1 > 0')
            ->whereRaw($city ? "city='" . $request->city . "'" : '1 > 0')
            ->whereRaw($district ? "district='" . $request->district . "'" : '1 > 0')
            ->whereRaw($tc ? "tc_name='" . $tc . "'" : '1 > 0')
            ->whereRaw($request->filled('agency_name') ? "agency_name like '%" . $request->agency_name . "%'" : '1 > 0')
            ->whereRaw($request->filled('') ? "t like '%" . $request->agency_name . "%'" : '1 > 0')
            ->whereRaw($request->filled('phone') ? "phone='" . $request->phone . "'" : '1 > 0')
            ->whereRaw($request->filled('name_surname') ? "name_surname like '%" . $request->name_surname . "%'" : '1 > 0');


        return DataTables::of($agencies)
            ->setRowClass(function ($agency) {
                return 'agency-item-' . $agency->id;
            })
            ->setRowId(function ($agency) {
                return 'agency-item-' . $agency->id;
            })
            //            ->addColumn('intro', 'Hi {{$name_surname}}')
            ->addColumn('regional_directorates', function ($agency) {
                return $agency->regional_directorates != '' ? "$agency->regional_directorates  B.M." : "";
            })
            ->addColumn('tc_name', function ($agency) {
                return $agency->tc_name != '' ? "$agency->tc_name  T.M." : "";
            })
            ->addColumn('edit', 'backend.who_is_who.columns.agencies_detail')
            ->rawColumns(['edit'])
            ->editColumn('city', function ($agency) {
                return $agency->city . '/' . $agency->district;
            })
            ->editColumn('created_at', function ($agency) {
                return Carbon::parse($agency->created_at)->format('Y-m-d H:i:s');
            })
            ->make(true);
    }

    public function agencyInfo(Request $request)
    {
        $agency_code = $request->agency_id;

        $data['agency'] = DB::select('CALL proc_agency_region_search(' . $agency_code . ')');

        $data['employees'] = DB::table('view_user_role')
            ->where('agency_code', $agency_code)
            ->orderBy('display_name', 'asc')
            ->get();

        return response()->json($data, 200);
    }

    public function transshipmentCenters(Request $request)
    {
        $data['agencies'] = Agencies::all();
        $data['tc'] = TransshipmentCenters::all();
        $data['roles'] = Roles::all();
        $data['cities'] = Cities::all();
        GeneralLog("'Kim Kimdir?' modülünde 'Transfer Merkezleri' sayfası görüntülendi.");
        return view('backend.who_is_who.transshipment_centers', compact('data'));
    }


    public function getTransshipmentCenters(Request $request)
    {
        $agencies = DB::table('view_transshipment_center_info');
        
        return DataTables::of($agencies)
            ->editColumn('tc_name', function ($agency) {
                return $agency->tc_name   . " TRM.";
            })
            ->editColumn('region_name', function ($agency) {
                return $agency->tc_name   . " BM.";
            })
            ->addColumn('edit', 'backend.who_is_who.columns.tc_detail')
            ->rawColumns(['edit'])
            ->editColumn('city', function ($agency) {
                return $agency->city . '/' . $agency->district;
            })
            ->make(true);
    }

    public function getTransshipmentCentersData(Request $request)
    {

        $data['transshipment'] = DB::table('view_transshipment_center_info')
            ->where('id', $request->transshipment_id)
            ->first();

        $data['director'] = DB::table('users')
            ->select(['name_surname', 'phone', 'email'])
            ->where('id', $data['transshipment']->tc_director_id)
            ->first();

        $data['assistant_director'] = DB::table('users')
        ->select(['name_surname', 'phone', 'email'])
        ->where('id',$data['transshipment']->tc_assistant_director_id)
        ->first();

        $data['agency_worker'] = DB::table('view_agency_region')
        ->where('tc_id',$data['transshipment']->id)
        ->get();

        return response()->json($data, 200);
    }
}
