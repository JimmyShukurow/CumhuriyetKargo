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
}
