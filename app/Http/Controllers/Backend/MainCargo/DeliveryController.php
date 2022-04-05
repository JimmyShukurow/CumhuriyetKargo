<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Actions\CKGSis\MainCargo\Delivery\AjaxTransaction\DeliveryAction;
use App\Actions\CKGSis\MainCargo\Delivery\AjaxTransaction\TransferAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\ProximityDegree;
use App\Models\TransferReason;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function index()
    {
        $branch = [];
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
        $transferReasons = TransferReason::all();

        GeneralLog('Teslimat sayfası görüntülendi.');
        return view('backend.main_cargo.delivery.index', compact(['branch', 'proximity', 'transferReasons']));
    }

    public function ajaxTransaction(Request $request, $transaction)
    {
        switch ($transaction) {
            case 'Delivery':
                return DeliveryAction::run($request);
                break;

            case 'Transfer':
                return TransferAction::run($request);
                break;

            default:
                return ['status' => 0, 'message' => 'no-case'];

        }
    }


}
