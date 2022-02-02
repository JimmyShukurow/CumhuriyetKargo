<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCargoMovementDetailsAction
{
    use AsAction;

    public function handle($request)
    {
        // ...
        $details = DB::table('cargo_movements')
        ->where('group_id', $request->group_id)
        ->get();

    return response()
        ->json($details, 200);
    }
}
