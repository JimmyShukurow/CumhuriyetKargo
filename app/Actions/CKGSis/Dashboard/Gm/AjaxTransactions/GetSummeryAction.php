<?php

namespace App\Actions\CKGSis\Dashboard\Gm\AjaxTransactions;

use App\Models\Agencies;
use App\Models\AgencyPayment;
use App\Models\Cargoes;
use App\Models\RegioanalDirectorates;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;
use phpDocumentor\Reflection\Types\Collection;

class GetSummeryAction
{
    use AsAction;

    public function handle($request)
    {
        return $data = GetSummeryInfoAction::run($request);
    }
}
