<?php

namespace App\Http\Controllers\Backend\Safe;

use App\Actions\CKGSis\Safe\AgencySafe\GetPaymentAppAction;
use App\Actions\CKGSis\Safe\GeneralSafe\GetAgencyPaymentAppAction;
use App\Actions\CKGSis\Safe\GeneralSafe\GetAgencyPaymentAppDetails;
use App\Actions\CKGSis\Safe\GeneralSafe\GetAgencyPaymentsAction;
use App\Actions\CKGSis\Safe\GeneralSafe\GetAgencyStatusAction;
use App\Actions\CKGSis\Safe\GeneralSafe\PaymentAppSetConfirmRejectAction;
use App\Actions\CKGSis\Safe\GeneralSafe\PaymentAppSetConfirmSuccessAction;
use App\Actions\CKGSis\Safe\GeneralSafe\PaymentAppSetConfirmWaitingAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cargoes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GeneralSafeController extends Controller
{
    public function index()
    {
        GeneralLog('Genel Kasa görüntülendi.');

        $data['payment_channels'] = DB::table('view_agency_payment_app_details')
            ->groupBy('payment_channel')
            ->get();

        $data['agencies'] = Agencies::orderBy('agency_name')->get();


        return view('backend.safe.general.index', compact('data'));
    }

    public function ajaxTransactions(Request $request, $val)
    {

        switch ($val) {
            case 'GetAgencySafeStatus':
                return GetAgencyStatusAction::run($request);
                break;

            case 'GetAgencyPaymentApps':
                return GetAgencyPaymentAppAction::run($request);
                break;

            case 'GetAgencyPaymentAppDetails':
                return GetAgencyPaymentAppDetails::run($request);
                break;

            case 'PaymentAppSetConfirmWaiting':
                return PaymentAppSetConfirmWaitingAction::run($request);
                return;

            case 'PaymentAppSetConfirmSuccess':
                return PaymentAppSetConfirmSuccessAction::run($request);
                return;

            case 'PaymentAppSetConfirmReject':
                return PaymentAppSetConfirmRejectAction::run($request);
                return;


            case 'GetAgencyPayments':
                return GetAgencyPaymentsAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => 0, 'message' => 'no-case!'], 200);
                break;
        }
    }
}
