<?php

namespace App\Http\Controllers\CKG_Mobile;

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
                // real val: '%OSJ%OS%FOS&FOSZO&$GU'
                $rules = ['bagCode' => 'required'];
                $validator = Validator::make($request->all(), $rules);


                if ($validator->fails()) {
                    return response()->json([
                        'status' => 0,
                        'errors' => $validator->getMessageBag()->toArray()
                    ], 200);
                }


                $bagCode = decryptTrackingNo($request->bagCode);

                $control  = DB::table('cargo_bags')
                    ->where('tracking_no', $bagCode)
                    ->first();

                if ($control == null) {
                    $data = [
                        'status' => 0,
                        'message' => 'Torba veya Çuval Bulunamadı!'
                    ];
                } else {
                    $data = [
                        'status' => 1,
                        'bag' => $control
                    ];
                }
                break;

            case 'LoadCargoToCargoBag':

                $rules = ['ctn' => 'required', 'cargo_bag_id' => 'required'];
                $validator = Validator::make($request->all(), $rules);


                if ($validator->fails()) {
                    return response()->json([
                        'status' => 0,
                        'errors' => $validator->getMessageBag()->toArray()
                    ], 200);
                }


                $ctn = decryptTrackingNo($request->ctn);
                $ctn = explode(' ', $ctn);
                $bagID = $request->cargo_bag_id;
                $bag = CargoBags::find($bagID);


                if ($bag == null) {
                    $data = [
                        'status' =>  0,
                        'message' => 'Çuval & Torba Bulunamadı!'
                    ];
                } else {

                    $cargo = DB::table('cargoes')
                        ->where('tracking_no', $ctn[0])
                        ->whereRaw('deleted_at is null')
                        ->where('confirm', '1')
                        ->first();


                    if ($cargo == null)
                        $data = [
                            'status' =>  0,
                            'message' => 'Kargo bulunamadı!'
                        ];
                    else {

                        #debit control
                        $debit = DB::table('debits')
                            ->where('cargo_id', $cargo->id)
                            ->where('agency_code', Auth::user()->agency_code)
                            ->first();



                        if ($debit == null) {


                            $data =  [
                                'status' =>  0,
                                'message' => 'Kargo zimmetinizde değil!'
                            ];
                        } else {

                            # Kargo Tipi (cargo_type) => Dosya - Mi
                            if ($cargo->cargo_type != 'Dosya' && $cargo->cargo_type != 'Mi')
                                $data = [
                                    'status' =>  0,
                                    'message' => 'Sadece Dosya veya Mi kargoları yükleyebilirsiniz!'
                                ];
                            else {

                                # load to bag
                                $insert = CargoBagDetails::create([
                                    'bag_id' => $bagID,
                                    'cargo_id' => $cargo->id,
                                    'part_no' => $ctn[1],
                                    'loader_user_id' => Auth::id(),
                                ]);

                                if ($insert)
                                    $data = ['status' => 1];
                                else
                                    $data = [
                                        'status' =>  0,
                                        'message' => 'İşlem başarısız oldu, lütfen daha sonra tekrar deneyiniz!'
                                    ];
                            }
                        }
                    }
                }
                break;

            case 'UnLoadCargoToCargoBag':
                $rules = ['ctn' => 'required', 'cargo_bag_id' => 'required'];
                $validator = Validator::make($request->all(), $rules);
                $data = [];

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 0,
                        'errors' => $validator->getMessageBag()->toArray()
                    ], 200);
                }

                $ctn = decryptTrackingNo($request->ctn);
                $ctn = explode(' ', $ctn);
                $bagID = $request->cargo_bag_id;

                $bag = CargoBags::find($bagID);

                if ($bag == null) {
                    $data = [
                        'status' =>  0,
                        'message' => 'Çuval & Torba Bulunamadı!'
                    ];
                } else {

                    $cargo = DB::table('cargoes')
                        ->where('tracking_no', $ctn[0])
                        ->whereRaw('deleted_at is null')
                        ->where('confirm', '1')
                        ->first();

                    if ($cargo == null)
                        $data = [
                            'status' =>  0,
                            'message' => 'Kargo bulunamadı!'
                        ];
                    else {
                        # kargo çuval & torbada var mı? 
                        $control = DB::table('cargo_bag_details')
                            ->where('cargo_id',  $cargo->id)
                            ->where('part_no', $ctn[1])
                            ->where('is_inside', '1')
                            ->first();

                        if ($control == null) {

                            $data = [
                                'status' =>  0,
                                'message' => 'Kargo ' . $bag->type . ' içerinde bulunamadı!'
                            ];
                        } else {

                            $update = CargoBagDetails::where('cargo_id', $cargo->id) //->first();
                                ->where('part_no', $ctn[1])
                                ->update(['is_inside' => '0', 'unloader_user_id' => Auth::user()->id, 'unloaded_time' => now()]);

                            if ($update)
                                $data = ['status' => 1];
                            else
                                $data = [
                                    'status' =>  0,
                                    'message' => 'İşlem başarısız oldu, lütfen daha sonra tekrar deneyiniz!'
                                ];
                        }
                    }
                }
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
