<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgencyPaymentsAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
        #$dateFilter = $request->dateFilter;
        $dateFilter = 'true';

        $appNo = $request->appNo;
        $agency = $request->agency;
        $paymentNo = $request->paymentNo;
        $paymentChannel = $request->paymentChannel;

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
            ->select(['agency_payments.*', 'users.name_surname', 'roles.display_name', 'agencies.agency_name'])
            ->join('users', 'users.id', '=', 'agency_payments.user_id')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('agencies', 'agencies.id', '=', 'agency_payments.agency_id')
            ->whereRaw($dateFilter == 'true' ? "agency_payments.created_at between '" . $firstDate . " 00:00:00'  and '" . $lastDate . " 23:59:59'" : ' 1 > 0')
            ->whereRaw($appNo ? 'app_id = ' . $appNo : ' 1 > 0')
            ->whereRaw($agency ? 'agency_id = ' . $agency : ' 1 > 0')
            ->whereRaw($paymentNo ? 'agency_payments.id = ' . $paymentNo : ' 1 > 0')
            ->whereRaw($paymentChannel ? "payment_channel = '" . $paymentChannel . "'" : ' 1 > 0');


        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('payment', function ($key) {
                return '<b class="text-primary">' . $key->payment . '₺</b>';
            })
            ->editColumn('app_id', function ($key) {
                return $key->app_id != null ? '<b style="color: #000; text-decoration: underline;" class="details-app cursor-pointer" id="' . $key->app_id . '">#' . $key->app_id . '</b>' : '';
            })
            ->editColumn('edit', function ($key) {
                return '<b style="text-decoration: underline;" class="text-primary cursor-pointer">DÜZENLE</b>';
            })
            ->editColumn('delete', function ($key) {
                return '<b style="text-decoration: underline;" id="' . $key->id . '" class="text-danger delete-payment cursor-pointer">Ödemeyi Sil</b>';
            })
            ->rawColumns(['app_id', 'description', 'payment', 'edit', 'delete'])
            ->make(true);
    }

}
