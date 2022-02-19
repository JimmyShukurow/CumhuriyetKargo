<?php

namespace App\Actions\CKGSis\Safe\AgencySafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPaymentAppAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
//        $dateFilter = $request->dateFilter;
        $dateFilter = 'true';


        if ($dateFilter == "true") {
            $diff = $firstDate->diffInDays($lastDate);
            if ($dateFilter) {
                if ($diff >= 90) {
                    return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 90 gün olabilir!'], 509);
                }
            }
        }

        $firstDate = substr($firstDate, 0, 10);
        $lastDate = substr($lastDate, 0, 10);


        $rows = DB::table('agency_payment_apps')
            ->join('users', 'users.id', '=', 'user_id')
            ->join('agencies', 'agencies.id', '=', 'agency_id')
            ->select(['agency_payment_apps.*', 'users.name_surname', 'agencies.agency_name']);


        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('add_files', function ($key) {
                return '';
            })
            ->rawColumns(['description'])
            ->make(true);
    }

}
