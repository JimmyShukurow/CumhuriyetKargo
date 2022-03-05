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
        $val = GetSummeryInfoAction::run($request);

        $rows = $val['data_full'];

        return datatables()->of($rows)
            ->editColumn('details',function ($key) {
                return '<button class="btn btn-primary" id="">Detay</button>';
            })
            ->rawColumns(['details'])
            ->make(true);
    }
}
