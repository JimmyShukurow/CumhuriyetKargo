<?php

namespace App\Http\Controllers\Backend\Expedition;

use App\Actions\CKGSis\Expedition\AjaxTransaction\GetOutGoingExpeditionsAction;
use App\Actions\CKGSis\Expedition\ExpeditionStoreAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpeditionController extends Controller
{
    public function create()
    {
        $branch = [];
        $branch = getUserBranchInfo();

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Sefer oluştur modülü görüntülendi.');
        return view('backend.expedition.create.create', compact(['branch', 'agencies', 'tc']));
    }

    public function store(Request $request)
    {
        return ExpeditionStoreAction::run($request);
    }

    public function outGoing($requestID = null)
    {
        $data['cities'] = Cities::all();
        $unit = '';

        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $unit = $agency->agency_name . ' ŞUBE';
        } else if (Auth::user()->user_type == 'Aktarma') {
            $agency = TransshipmentCenters::find(Auth::user()->tc_code);
            $unit = $agency->tc_name . ' TRM';
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('Giden seferler sayfası görüntülendi');
        return view('backend.expedition.outgoing.outgoing_expeditions', compact(['data', 'unit', 'agencies', 'tc', 'requestID']));
    }

    public function ajaxTransactions(Request $request, $val)
    {
        switch ($val) {
            case 'GetOutGoingExpeditions':
                return GetOutGoingExpeditionsAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => 0, 'message' => 'no-case'], 200);

        }
    }
}
