<?php

namespace App\Actions\CKGMobile\Expedition;

use App\Models\ExpeditionCargo;
use Lorisleiva\Actions\Concerns\AsAction;

class LoadCargoToExpeditionAction
{
    use AsAction;

    public function handle($request)
    {
       return ExpeditionCargo::create($request);
    }
}
