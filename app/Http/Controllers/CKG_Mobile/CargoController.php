<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Http\Controllers\Controller;
use App\Models\Cargoes;
use Illuminate\Http\Request;
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
                    $cargo['cargo']->tracking_no = TrackingNumberDesign($tracking_no);

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

            default:
                $data = 'no-case';
                break;
        }

        return response()
            ->json($data, 200);


    }
}
