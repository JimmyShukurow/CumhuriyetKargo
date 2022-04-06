<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\CargoMovements;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCancelledCargoInfoAction
{
    use AsAction;

    public function handle($request)
    {
        $data = GetCargoInfoAction::run($request);

        return response()
            ->json($data, 200);
    }
}
