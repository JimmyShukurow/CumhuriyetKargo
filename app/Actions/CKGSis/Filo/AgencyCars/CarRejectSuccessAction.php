<?php

namespace App\Actions\CKGSis\Filo\AgencyCars;

use App\Models\TcCars;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CarRejectSuccessAction
{
    use AsAction;

    public function handle($request)
    {
        DB::beginTransaction();
        try {
            $car = TcCars::find($request->id);
            $car->confirm = "-1";
            $car->confirmed_user = Auth::user()->id;
            $car->confirmed_date = now();
            $car->save();
            DB::commit();
            return response()->json(['status' => 1, 'message' => 'Araç Reddedildi!']);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['status' => 0, 'message' => 'Araç Reddetme sırasında hata oluştu!']);
        }
    }
}
