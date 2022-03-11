<?php

namespace App\Actions\CKGMobile\Expedition;

use App\Models\ExpeditionCargo;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadCargoToExpeditionAction
{
    use AsAction;

    public function handle($fields)
    {
        $expedition = ExpeditionCargo::create($fields);
        if ($expedition){
            return response()->json([
                'status' => 1,
                'expedition' => $expedition,
            ]);
        }
    }
}
