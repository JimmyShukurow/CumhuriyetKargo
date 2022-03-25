<?php

namespace App\Http\Controllers\CKG_Barcoder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\CKG_Barcoder\Transaction\GetCargoInfoAction;
use App\Actions\CKG_Barcoder\Transaction\GetMultipleCargoInfoAction;

class CKGBarcoderController extends Controller
{
    public function transactions(Request $request, $transaction)
    {

        switch ($transaction) {

            case 'GetCargoInfo':
                return GetCargoInfoAction::run($request);
                break;

            case 'GetMultipleCargoInfo':
                return GetMultipleCargoInfoAction::run($request);
                break;

            default:
                return response()->json(['status' => 0, 'message' => 'No-Case!']);
        }
    }
}
