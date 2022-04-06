<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction;

use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTaxOfficesAction
{
    use AsAction;

    public function handle($request)
    {
        $data = DB::table('tax_offices')
            ->where('office', 'like', '%' . $request->SearchTerm . '%')
            ->get(['id', 'office', 'city', 'district']);


        return $jsonData = $data;
    }
}
