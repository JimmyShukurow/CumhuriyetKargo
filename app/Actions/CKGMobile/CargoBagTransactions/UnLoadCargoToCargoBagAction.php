<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Models\CargoBags;
use App\Models\CargoBagDetails;
use App\Models\Cargoes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UnLoadCargoToCargoBagAction
{
    use AsAction;

    public function handle( $request)
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
                # kargo çuval & torbada var mı? 
                $control = CargoBagDetails::where('cargo_id',  $cargo->id)->where('part_no', $ctn[1])->where('is_inside', '1')->first();

                if ($control == null) {

                    return [
                        'status' =>  0,
                        'message' => 'Kargo ' . $bag->type . ' içerinde bulunamadı!'
                    ];
                } else {
                    $bag->update(
                        [
                            'last_opener' => Auth::id(),
                            'last_opening_date' => now(),
                        ]
                    );

                    $update = CargoBagDetails::where('cargo_id', $cargo->id) //->first();
                        ->where('part_no', $ctn[1])
                        ->update(['is_inside' => '0', 'unloader_user_id' => Auth::user()->id, 'unloaded_time' => now()]);

                    if ($update){
                        RegisterMovementAction::run($ctn[0],$bag, $cargo->id, Auth::id(),1, Str::random(10), 'unload_cargo_bag', 2);
                        return ['status' => 1];
                    }
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
