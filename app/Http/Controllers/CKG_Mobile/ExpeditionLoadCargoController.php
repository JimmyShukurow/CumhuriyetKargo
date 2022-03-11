<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Actions\CKGMobile\Expedition\LoadCargoToExpeditionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expedition\LoadCargoToExpeditionRequest;
use App\Models\Cargoes;
use App\Models\Expedition;
use App\Models\ExpeditionCargo;
use Illuminate\Support\Facades\Auth;

class ExpeditionLoadCargoController extends Controller
{
    public function loadCargo(LoadCargoToExpeditionRequest $request){
        $validated = $request->validated();

        $ctn = decryptTrackingNo($validated['ctn']);
        $ctn = explode(' ', $ctn);


        $expedition = Expedition::find($validated['expedition_id']);

        if ($expedition->done == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Bu Sefer Bittiğinden Dolayı İşlem Yapamazsınız',
            ]);
        }

        $cargo = Cargoes::where('tracking_no', $ctn[0])->first();
        $cargoes = ExpeditionCargo::where('cargo_id', $cargo->id)->where('unloading_at', null)->get()->pluck('part_no');
        if ($cargoes->contains($ctn[1])){
            return response()->json([
                'status' => 0,
                'message' => 'Bu Kargo Zaten Araçta Var',
            ]);
        }

        $fields = $request->only('expedition_id');
        $fields['cargo_id'] = $cargo->id;
        $fields['part_no'] = $ctn[1];
        $fields['user_id'] = Auth::id();

        return LoadCargoToExpeditionAction::run($fields);
    }
}
