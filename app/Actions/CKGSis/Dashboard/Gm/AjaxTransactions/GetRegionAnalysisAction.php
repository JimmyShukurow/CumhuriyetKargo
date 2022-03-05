<?php

namespace App\Actions\CKGSis\Dashboard\Gm\AjaxTransactions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRegionAnalysisAction
{
    use AsAction;

    public function handle($request)
    {
//        $val = collect(GetSummeryAction::run($request));
//
//        return $val['']
//        echo "<pre>";
//        print_r($val);
//        echo "</pre>";

        $val = json_decode(GetSummeryAction::run($request));

        return $val;

        return false;

        $rows = $val;

        return datatables()->of($rows)
            ->make(true);


    }
}
