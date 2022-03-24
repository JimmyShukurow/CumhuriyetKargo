<?php

namespace App\Actions\CKGMobile\Expedition;

use App\Models\ExpeditionCargo;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadCargoToExpeditionAction
{
    use AsAction;

    public function handle($fields)
    {

        $cargoes = ExpeditionCargo::where('expedition_id',$fields['expedition_id'])->where('cargo_id', $fields['cargo_id'])->where('unloading_at', null)->get()->pluck('part_no');
        if ($cargoes->contains($fields['part_no'])) {
            return response()->json([
                'status' => 0,
                'message' => 'Bu Kargo Zaten Araçta Var',
            ]);
        }


        $expedition = ExpeditionCargo::create($fields);
        if ($expedition){
            return response()->json([
                'status' => 1,
                'expedition' => $expedition,
            ]);
        }



    }
}
