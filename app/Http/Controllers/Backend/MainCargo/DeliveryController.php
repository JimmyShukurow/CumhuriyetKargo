<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Actions\CKGSis\MainCargo\Delivery\AjaxTransaction\DeliveryAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\ProximityDegree;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function index()
    {
        $damage_types = DB::table('htf_damage_types')->get();
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
        }

        $proximity = ProximityDegree::all();

        GeneralLog('Teslimat sayfası görüntülendi.');
        return view('backend.main_cargo.delivery.index', compact(['damage_types', 'transactions', 'branch', 'proximity']));
    }

    public function ajaxTransaction(Request $request, $transaction)
    {
        switch ($transaction) {
            case 'Delivery':
                return DeliveryAction::run($request);
                break;

            default:
                return ['status' => 0, 'message' => 'no-case'];

        }
    }


}
