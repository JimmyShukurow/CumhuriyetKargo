<?php

namespace App\Actions\CKGMobile\Expedition;

use App\Models\ExpeditionCargo;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class UnloadCargoFromExpedition
{
    use AsAction;

    public function handle($cargoID, $partNo, $expeditionID)
    {
        $expedition = ExpeditionCargo::where('cargo_id', $cargoID)
            ->where('part_no', $partNo)
            ->where('expedition_id', $expeditionID)
            ->where('unloading_at', null)
            ->first();
        $expedition->update(['unloading_user_id' => Auth::id(), 'unloading_at' => now()]);
        if ($expedition){
            return response()->json([
                'status' => 1,
                'expedition' => $expedition,
            ]);
        }
    }
}
