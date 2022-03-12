<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Actions\CKGMobile\Expedition\LoadCargoToExpeditionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expedition\LoadCargoToExpeditionRequest;
use App\Http\Resources\CKGMobile\Expedition\ExpeditionResource;
use App\Models\Cargoes;
use App\Models\Expedition;
use App\Models\ExpeditionCargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpeditionLoadCargoController extends Controller
{
    public function loadCargo(LoadCargoToExpeditionRequest $request)
    {
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
        if ($cargo == null)
            return response()->json([
                'status' => 0,
                'message' => 'Kargo bulunamadı!',
            ]);


        $cargoes = ExpeditionCargo::where('cargo_id', $cargo->id)->where('unloading_at', null)->get()->pluck('part_no');
        if ($cargoes->contains($ctn[1])) {
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

    public function readExpedition(Request $request)
    {
        $plaka = tr_strtoupper($request->plaka);

        if ($plaka == null)
            return response()->json([
                'status' => 0,
                'message' => 'Plaka alanı zorunludur!',
            ]);

        $expedition = Expedition::with('car', 'cargoes')
            ->whereHas('car', function ($query) use ($plaka) {
                $query->where('plaka', $plaka);
            })
            ->where('done', 0)
            ->first();


        if ($expedition == null) {
            return response()->json([
                'status' => 0,
                'message' => 'Bu araca sefer oluşturulmamış!',
            ]);
        }

        if ($expedition->car->status != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Araç aktif değil, işlem yapamazsınız!',
            ]);
        }
        if ($expedition->car->confirm != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Araç onaylı değil, işlem yapamazsınız!',
            ]);
        }

        return response()->json([
            'status' => 1,
            'expedetion' => new ExpeditionResource($expedition),
        ]);
    }
}
