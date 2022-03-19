<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction;

use App\Models\Currents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class ChangeStatusAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'status' => 'required|in:0,1',
            'currentID' => 'required|numeric'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => '-1',
                'errors' => $validator->getMessageBag()->toArray()
            ], 200);
        }

        $update = Currents::find(intval($request->currentID))
            ->update(['status' => $request->status]);

        if ($update) {

            $statu = $request->status == '1' ? 'aktif' : 'pasif';
            $current = Currents::find(intval($request->currentID));
            $properties = [
                'Eylemi gerçekleştiren' => Auth::user()->name_surname,
                'id\'si' => Auth::id(),
                'İşlem Yapılan Kullanıcı' => $current->name,
                'Statü' => $statu
            ];

            $log = $current->name . " İsimli gönderici cari " . $statu . ' hale getirildi';
            activity()
                ->performedOn($current)
                ->inLog('Current Enabled-Disabled')
                ->withProperties($properties)
                ->log($log);

            return response()->json(['status' => 1], 200);
        } else
            return response()->json([
                'status' => 0,
                'message' => "Bir hata oluştu, lütfen daha sonra tekrar deneyin!"
            ], 200);
    }
}
