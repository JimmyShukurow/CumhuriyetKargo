<?php

namespace App\Actions\CKGSis\Safe\AgencySafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetMyPaymentsAction
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


        $rows = DB::table('agency_payments')
            ->join('users', 'users.id', '=', 'agency_payments.user_id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('agencies', 'agencies.id', '=', 'agency_payments.agency_id')
            ->select(['agency_payments.*', 'users.name_surname', 'roles.display_name', 'agencies.agency_name'])
            ->whereRaw($dateFilter == 'true' ? "agency_payments.created_at between '" . $firstDate . " 00:00:00'  and '" . $lastDate . " 23:59:59'" : ' 1 > 0');

        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('payment', function ($key) {
                return '<b class="text-primary">' . $key->payment . '₺</b>';
            })
            ->editColumn('name_surname', function ($key) {
                return $key->name_surname . ' (' . $key->display_name . ')';
            })
            ->editColumn('delete', function ($key) {
                return '<span style="text-decoration: underline" class="text-danger font-weight-bold cursor-pointer text-center delete-app" id="' . $key->id . '">Başvuruyu Sil</span>';
            })
            ->rawColumns(['description', 'payment'])
            ->make(true);
    }
}
