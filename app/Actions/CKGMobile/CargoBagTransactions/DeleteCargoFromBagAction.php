<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Models\CargoBagDetails;
use App\Models\CargoBags;
use App\Models\Cargoes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteCargoFromBagAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = ['ctn' => 'required', 'bag_id' => 'required', 'deleted_from' => 'required'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'errors' => $validator->getMessageBag()->toArray()
            ], 200);
        }

        $ctn = explode(' ', decryptTrackingNo($request->ctn));
        $trackin_no = $ctn[0];
        $part_no =  $ctn[1];
        $bagID = $request->bag_id;
 

        #check if exist the cargo 
        $cargo_bag = CargoBags::find($bagID);
        $cargo = $cargo_bag->bagDetails->where('tracking_no', $trackin_no)->first();

        
        if ($cargo == null) 
            return response()
                ->json(['status' => 0, 'message' => 'Kargo bulunamadı'], 200);

        $bag_details = CargoBagDetails::where('cargo_id', $cargo->id)->where('is_inside', '1')->get();

        $ids = $bag_details->pluck('id');
        if ($bag_details != []) {
            $bag_details = $bag_details->map(function($item) use($request) {
                $item->update(['deleted_from' => $request->deleted_from, 'is_inside' => '0', 'deleted_user' => Auth::id()]);
                return $item->delete();
            });
            // $cargo_bag->update(['deleted_from' => $request->deleted_from]);
            // $cargo_bag->delete();
            return [
                'id' => $ids,
                'cargo_id' => $cargo->id,
                'status' => 1,
                'message' => 'Kargo Silindi',
            ];
        } else {
            return [
                'status' => 0,
                'message' => 'Hata oluştu!',
            ];
        }
    }
}
