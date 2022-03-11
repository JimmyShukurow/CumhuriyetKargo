<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetOutGoingExpeditionsAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
        #$dateFilter = $request->dateFilter;
        $dateFilter = 'true';

        $appNo = $request->appNo;
        $confirm = $request->confirm;
        $paymentChannel = $request->paymentChannel;
        $agency = $request->agency;

        if ($dateFilter == "true") {
            $diff = $firstDate->diffInDays($lastDate);
            if ($dateFilter) {
                if ($diff >= 120) {
                    return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 120 gün olabilir!'], 509);
                }
            }
        }
        $firstDate = substr($firstDate, 0, 10);
        $lastDate = substr($lastDate, 0, 10);


        $rows = Expedition::with(['car', 'departureBranch'])
            ->get();

        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->rawColumns(['description', 'test', 'add_files', 'confirm', 'paid', 'confirm_paid', 'delete'])
            ->make(true);
    }
}
