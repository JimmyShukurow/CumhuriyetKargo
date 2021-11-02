<?php

namespace App\Http\Controllers\Backend\OfficialReports;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficialReportController extends Controller
{
    public function createHTF(Request $request)
    {
        $damage_types = DB::table('damage_types')->get();
        $transactions = DB::table('htf_transactions_made')->get();

        $branch = [];
        ## Get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $branch = [
                'code' => $agency->agency_code,
                'city' => $agency->city,
                'name' => $agency->agency_name,
                'type' => 'ŞUBE'
            ];
        } else {
            $tc = TransshipmentCenters::find(Auth::user()->tc_code);
            $branch = [
                'code' => $tc->tc_code,
                'city' => $tc->city,
                'name' => $tc->agency_name,
                'type' => 'TRM.'
            ];
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('HTF oluştur sayfası görüntülendi.');
        return view('backend.OfficialReports.htf_create', compact(['damage_types', 'transactions', 'branch', 'agencies', 'tc']));
    }

}