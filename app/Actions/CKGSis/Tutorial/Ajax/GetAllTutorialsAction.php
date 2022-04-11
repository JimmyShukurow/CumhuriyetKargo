<?php

namespace App\Actions\CKGSis\Tutorial\Ajax;

use App\Actions\CKGSis\Expedition\AjaxTransaction\FilterExpeditionAction;
use App\Actions\CKGSis\Expedition\AjaxTransaction\FilterExpeditionArrivalAction;
use App\Actions\CKGSis\Expedition\AjaxTransaction\GetExpeditionActions;
use App\Models\Tutorial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsController;

class GetAllTutorialsAction
{
    use AsAction;
    use AsController;

    public function asController(Request $request)
    {
        return $this->handle($request);
    }

    public function handle(Request $request)
    {


        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
        #$dateFilter = $request->dateFilter;
        $dateFilter = 'true';

        $plaka = $request->plaka;
        $serialNo = $request->serialNo;
        $creator = $request->creator;
        $doneStatus = $request->doneStatus;
        $departureBranch = $request->departureBranch;
        $arrivalBranch = $request->arrivalBranch;

        if ($dateFilter == "true") {
            $diff = $firstDate->diffInDays($lastDate);
            if ($dateFilter) {
                if ($diff >= 365) {
                    return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 365 gün olabilir!'], 509);
                }
            }
        }
        $firstDate = substr($firstDate, 0, 10) . ' 00:00:00';
        $lastDate = substr($lastDate, 0, 10) . ' 23:59:59';


        $rows = Tutorial::all();



        return datatables()->of($rows)

            ->make(true);
    }


}
