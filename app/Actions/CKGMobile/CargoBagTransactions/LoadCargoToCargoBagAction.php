<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Http\Resources\CargoBagDetailsResource;
use App\Models\Cargoes;
use App\Models\CargoBags;
use App\Models\CargoBagDetails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadCargoToCargoBagAction
{
    use AsAction;

    public function handle( $request )
    {
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
            return [
                'status' =>  0,
                'message' => 'Çuval & Torba Bulunamadı!'
            ];
        } else {

            $cargo = Cargoes::where('tracking_no', $ctn[0])->where('confirm', '1')->first();

            if ($cargo == null)
                return [
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


                    return  [
                        'status' =>  0,
                        'message' => 'Kargo zimmetinizde değil!'
                    ];
                } else {

                    # Kargo Tipi (cargo_type) => Dosya - Mi
                    if ($cargo->cargo_type != 'Dosya' && $cargo->cargo_type != 'Mi')
                        return [
                            'status' =>  0,
                            'message' => 'Sadece Dosya veya Mi kargoları yükleyebilirsiniz!'
                        ];
                    else {
                        
                        #check if its exists
                        $check_if_its_exist = CargoBagDetails::where('cargo_id', $cargo->id)->where('part_no', $ctn[1])->first();
                        if($check_if_its_exist){
                            return [
                                'status' => 0,
                                'message' => 'Mükerrer yükleme işlemi engellendi',
                            ];
                        }
                        
                        # load to bag
                        $insert = CargoBagDetails::create([
                            'bag_id' => $bagID,
                            'cargo_id' => $cargo->id,
                            'part_no' => $ctn[1],
                            'loader_user_id' => Auth::id(),
                        ]);

                        $cargo_bag = CargoBags::find($bagID);

                        if ($insert)
                            return [
                                'status' => 1,
                                'cargoes' => CargoBagDetailsResource::collection($cargo_bag->bagDetails),
                            ];
                        else
                            return [
                                'status' =>  0,
                                'message' => 'İşlem başarısız oldu, lütfen daha sonra tekrar deneyiniz!'
                            ];
                    }
                }
            }
        }
    }
}
