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

        $val = GetSummeryInfoAction::run($request);

        return $val['data_full'];

        return false;

        $rows = $val;

        return datatables()->of($rows)
            ->make(true);


    }
}
