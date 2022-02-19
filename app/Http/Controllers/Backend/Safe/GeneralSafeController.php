<?php

namespace App\Http\Controllers\Backend\Safe;

use App\Actions\CKGSis\Safe\GeneralSafe\GetAgencyStatusAction;
use App\Http\Controllers\Controller;
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

        return view('backend.safe.general.index');
    }

    public function ajaxTransactions(Request $request, $val)
    {

        switch ($val) {
            case 'GetAgencySafeStatus':
                return GetAgencyStatusAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => 0, 'message' => 'no-case!'], 200);
                break;
        }
    }
}
