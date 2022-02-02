<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCurrentsAction
{
    use AsAction;

    public function handle($request)
    {
        $request->currentSearchTerm = tr_strtoupper(tr_strtolower(enCharacters(urlCharacters($request->currentSearchTerm))));

                $Currents = DB::table('currents')
                    ->where('name', 'like', '%' . $request->currentSearchTerm . '%')
                    ->whereRaw('deleted_at is null')
                    ->where('confirmed', '1')
                    ->where('current_type', 'Gönderici')
                    ->limit(50)
                    ->orderBy('name')
                    ->distinct()
                    ->get(['name']);

                return response()
                    ->json($Currents, 200);
    }
}
