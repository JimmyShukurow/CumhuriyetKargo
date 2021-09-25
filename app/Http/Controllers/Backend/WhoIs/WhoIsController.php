<?php

namespace App\Http\Controllers\Backend\WhoIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use App\Models\Roles;

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
            || ($request->user_type && $request->user_type != '')) {
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
                ->select(['name_surname', 'display_name'])
                ->where('agency_code', $data['user']->agency_code)
                ->where('role_id', 20)
                ->orderBy('created_at', 'asc')
                ->first();

        else if ($data['user']->user_type == 'Aktarma')
            $data['director'] = DB::table('transshipment_centers')
                ->select(['transshipment_centers.*', 'view_users_all_info.name_surname', 'view_users_all_info.display_name'])
                ->join('view_users_all_info', 'view_users_all_info.id', '=', 'transshipment_centers.tc_director_id')
                ->where('transshipment_centers.id', $data['user']->tc_code)
                ->first();


        $data['user_log'] = DB::table('activity_log')
            ->where('causer_id', $request->user)
            ->limit(30)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($data, 200);

    }


}
