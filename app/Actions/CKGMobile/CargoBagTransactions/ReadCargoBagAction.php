<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Models\CargoBags;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

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

         if ($control == null) {
             return [
                 'status' => 0,
                 'message' => 'Torba veya Çuval Bulunamadı!'
             ];
         } else {
             return [
                 'status' => 1,
                 'bag' => $control
             ];
         }
    }
}
