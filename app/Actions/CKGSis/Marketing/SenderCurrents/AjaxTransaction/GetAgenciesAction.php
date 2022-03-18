<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction;

use App\Models\Agencies;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgenciesAction
{
    use AsAction;

    public function handle($request)
    {
        $request->SearchTerm = tr_strtoupper(tr_strtolower(enCharacters(urlCharacters($request->SearchTerm))));
        $data = Agencies::where('agency_name', 'like', '%' . $request->SearchTerm . '%')
            ->whereRaw('deleted_at is null')
            ->limit(50)
            ->get(['id', 'agency_name', 'city', 'district']);
        return $jsonData = $data;
    }
}
