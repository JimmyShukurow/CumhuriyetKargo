<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Actions\CKGMobile\CargoBagTransactions\LoadCargoToCargoBagAction;
use App\Actions\CKGMobile\CargoBagTransactions\ReadCargoBagAction;
use App\Actions\CKGMobile\CargoBagTransactions\UnLoadCargoToCargoBagAction;
use App\Http\Controllers\Controller;
use App\Models\CargoBagDetails;
use App\Models\CargoBags;
use App\Models\Cargoes;
use App\Models\CargoMovements;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CargoController extends Controller
{
    public function cargoTransaction($val, Request $request)
    {

        switch ($val) {

            case 'CargoGeneralInfo':
                $tracking_no = str_replace(' ', '', $request->ctn);

                $cargo['cargo'] = Cargoes::where('tracking_no', $tracking_no)
                    ->first();

                if ($cargo == null)
                    $data = ['status' => '0', 'message' => 'Kargo bulunamadı!'];
                else {
                    //                    $cargo['movements'] = CargoMovements::where('ctn', $tracking_no)
                    //                        ->get();

                    $cargo['movements'] = DB::table('cargo_movements')
                        ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
                        ->groupBy('group_id')
                        ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
                        ->where('ctn', '=', $tracking_no)
                        ->get();

                    //                    $cargo['cargo']->tracking_no = TrackingNumberDesign($tracking_no);

                    $cargo['cargo']->collectible = $cargo['cargo']->collectible == '0' ? 'HAYIR' : 'EVET';

                    $cargo['sender'] = DB::table('currents')
                        ->select(['current_code', 'tckn', 'category'])
                        ->where('id', $cargo['cargo']->sender_id)
                        ->first();
                    $cargo['sender']->current_code = CurrentCodeDesign($cargo['sender']->current_code);

                    $cargo['receiver'] = DB::table('currents')
                        ->select(['current_code', 'tckn', 'category'])
                        ->where('id', $cargo['cargo']->receiver_id)
                        ->first();
                    $cargo['receiver']->current_code = CurrentCodeDesign($cargo['receiver']->current_code);

                    $cargo['creator'] = DB::table('view_users_all_info')
                        ->select(['name_surname', 'display_name'])
                        ->where('id', $cargo['cargo']->creator_user_id)
                        ->first();

                    $cargo['departure'] = DB::table('agencies')
                        ->select(['agency_code', 'agency_name', 'city', 'district'])
                        ->where('id', $cargo['cargo']->departure_agency_code)
                        ->first();

                    $cargo['departure_tc'] = DB::table('transshipment_centers')
                        ->select(['city', 'tc_name'])
                        ->where('id', $cargo['cargo']->departure_tc_code)
                        ->first();

                    $cargo['arrival'] = DB::table('agencies')
                        ->select(['agency_code', 'agency_name', 'city', 'district'])
                        ->where('id', $cargo['cargo']->arrival_agency_code)
                        ->first();

                    $cargo['arrival_tc'] = DB::table('transshipment_centers')
                        ->select(['city', 'tc_name'])
                        ->where('id', $cargo['cargo']->arrival_tc_code)
                        ->first();

                    $cargo['add_services'] = DB::table('cargo_add_services')
                        ->select(['service_name', 'price'])
                        ->where('cargo_tracking_no', str_replace(' ', '', $cargo['cargo']->tracking_no))
                        ->get();

                    $data = [
                        'status' => '1',
                        'movements' => $cargo['movements'],
                        'cargo' => $cargo['cargo'],
                        'sender' => $cargo['receiver'],
                        'departure' => $cargo['departure'],
                        'creator' => $cargo['creator'],
                        'departure' => $cargo['departure'],
                        'departure_tc' => $cargo['departure_tc'],
                        'arrival' => $cargo['arrival'],
                        'arrival_tc' => $cargo['arrival_tc'],
                        'add_services' => $cargo['add_services'],
                    ];
                }
                break;

            case 'CargoMovements':
                $tracking_no = str_replace(' ', '', $request->ctn);

                $data['cargo_movements'] = DB::table('cargo_movements')
                    ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
                    ->groupBy('group_id')
                    ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
                    ->where('ctn', '=', $tracking_no)
                    ->get();
                break;

            default:
                $data = 'no-case';
                break;
        }

        return response()
            ->json($data, 200);
    }

    public function caroBagTransactions($val = null, Request $request)
    {
        $data = [];

        switch ($val) {
            case 'ReadCargoBag':
                    return ReadCargoBagAction::run($request);
                break;

            case 'LoadCargoToCargoBag':
                    return LoadCargoToCargoBagAction::run($request);
                break;

            case 'UnLoadCargoToCargoBag':
                    return UnLoadCargoToCargoBagAction::run($request);
                break;

            default:
                $data = 'no-case';
                break;
        }


        return response()->json($data, 200);
    }

    public function loadCargoBag(Request $request)
    {
        return true;
    }
}
