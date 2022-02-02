<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Http\Resources\CargoBagDetailsResource;
use App\Http\Resources\CargoBagResource;
use App\Models\CargoBags;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

use function PHPSTORM_META\map;

class ReadCargoBagAction
{
    use AsAction;

    public function handle( $request )
    {
         // real val: '%OSJ%OS%FOS&FOSZO&$GU'
         $rules = ['bagCode' => 'required'];
         $validator = Validator::make($request->all(), $rules);


         if ($validator->fails()) {
             return response()->json([
                 'status' => 0,
                 'errors' => $validator->getMessageBag()->toArray()
             ], 422);
         }


         $bagCode = decryptTrackingNo($request->bagCode);

         $control  = CargoBags::where('tracking_no', $bagCode)->first();
         $cargoes = $control->bagDetails;

         if ($control == null) {
             return [
                 'status' => 0,
                 'message' => 'Torba veya Çuval Bulunamadı!'
             ];
         } else {
             return [
                 'status' => 1,
                 'bag' => new CargoBagResource($control),
                 'cargoes' => CargoBagDetailsResource::collection($cargoes),
             ];
         }
    }
}
