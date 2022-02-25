<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgencyStatusAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
        #$dateFilter = $request->dateFilter;
        $dateFilter = 'true';

        if ($dateFilter == "true") {
            $diff = $firstDate->diffInDays($lastDate);
            if ($dateFilter) {
                if ($diff >= 30) {
                    return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 30 gün olabilir!'], 509);
                }
            }
        }
        $firstDate = substr($firstDate, 0, 10);
        $lastDate = substr($lastDate, 0, 10);


        $rows = DB::table('view_agency_safe_status');


        return datatables()->of($rows)
            ->editColumn('endorsement', function ($key) {
                return round($key->endorsement, 2);
            })
            ->editColumn('cash_amount', function ($key) {
                return round($key->cash_amount, 2);
            })
            ->editColumn('pos_amount', function ($key) {
                return round($key->pos_amount, 2);
            })
            ->editColumn('debt', function ($key) {
                return round($key->debt, 2);
            })
            ->editColumn('safe_status', function ($key) {
                return $key->safe_status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>';
            })
            ->addColumn('detail', function ($key) {
                return '<b style="text-decoration: underline;" class="cursor-pointer ml-3 text-primary safe-detail" id="' . $key->id . '">Detay</b>';
            })
            ->rawColumns(['safe_status', 'detail'])
            ->make(true);
    }
}
