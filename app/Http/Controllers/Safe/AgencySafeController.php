<?php

namespace App\Http\Controllers\Safe;

use App\Actions\CKGSis\Safe\AgencySafe\GetPendingCollectionsAction;
use App\Actions\CKGSis\Safe\AgencySafe\GetCollectionsAction;
use App\Http\Controllers\Controller;
use App\Models\Cities;
use Illuminate\Http\Request;

class AgencySafeController extends Controller
{

    public function index()
    {
        GeneralLog('Acente Kasası görüntülendi.');
        return view('backend.safe.agency.index');
    }

    public function ajaxTransactions(Request $request, $val)
    {

        switch ($val) {
            case 'GetCollections':
                return GetCollectionsAction::run($request);
                break;

            case 'GetPendingCollections':
                return GetPendingCollectionsAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => '0', 'message' => 'no-case!'], 200);
                break;
        }

    }
}
