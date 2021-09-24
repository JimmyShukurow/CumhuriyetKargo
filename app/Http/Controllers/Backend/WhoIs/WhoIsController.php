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

        GeneralLog('GM Kullanıcılar sayfası görüntülendi.');

        return view('backend.who_is_who.index', compact('data'));
    }

    public function getUsers(Request $request)
    {


        $status = $request->status == 'Aktif' ? '1' : '0';

        if (($request->name_surname && $request->name_surname != '') || ($request->agency && $request->agency != '')
            || ($request->tc && $request->tc != '') || ($request->role && $request->role != '') || ($request->status && $request->status != '')
            || ($request->user_type && $request->user_type != '')) {
            $users = DB::table('view_users_all_info')
                ->select([
                    'id','name_surname','display_name','email','phone','status','branch_city','branch_district','branch_name','user_type'
                ])
                ->whereRaw($request->filled('agency') ? 'agency_code=' . $request->agency : '1 > 0')
                ->whereRaw($request->filled('tc') ? 'tc_code=' . $request->tc : '1 > 0')
                ->whereRaw($request->filled('role') ? 'role_id=' . $request->role : '1 > 0')
                ->whereRaw($request->filled('status') ? "view_users_all_info.`status`='" . $status . "'" : '1 > 0')
                ->whereRaw($request->filled('user_type') ? "user_type='" . $request->user_type . "'" : '1 > 0')
                ->whereRaw($request->filled('name_surname') ? "name_surname like '%" . $request->name_surname . "%'" : '1 > 0');
        } else {
            $users = DB::table('view_users_all_info')
                ->select([
                    'id','name_surname','display_name','email','phone','status','branch_city','branch_district','branch_name','user_type'
                ]);
        }
        return datatables()->of($users)
            ->setRowId(function ($user) {
                return "user-item-" . $user->id;
            })
            ->editColumn('status', function ($user) {
                return $user->status == '1' ? 'Aktif' : 'Pasif';
            })
            ->addColumn('edit', 'backend.users.gm.columns.edit')
            ->rawColumns(['edit'])
            ->make(true);

    }
}
