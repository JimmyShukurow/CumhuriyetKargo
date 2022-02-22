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
        $confirm = $request->confirm;
        $paymentChannel = $request->paymentChannel;
        $agency = $request->agency;

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
            ->join('agencies', 'agencies.id', '=', 'agency_payments.agency_id');


            /*->whereRaw($dateFilter == 'true' ? "created_at between '" . $firstDate . " 00:00:00'  and '" . $lastDate . " 23:59:59'" : ' 1 > 0')
            ->whereRaw($appNo != null ? 'id=' . $appNo : ' 1 > 0')
            ->whereRaw($confirm != null ? "confirm='" . $confirm . "'" : ' 1 > 0')
            ->whereRaw($paymentChannel != null ? "payment_channel='" . $paymentChannel . "'" : ' 1 > 0')
            ->whereRaw($agency != null ? 'agency_id=' . $agency : ' 1 > 0');*/


        return datatables()->of($rows)
//            ->editColumn('description', function ($key) {
//                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
//            })
//            ->editColumn('add_files', function ($key) {
//                $string = "";
//                if ($key->file1 != null)
//                    $string = '<a target="_blank" href="/files/app_files/' . $key->file1 . '">Ek1</a>';
//                if ($key->file2 != null)
//                    $string .= ' <a target="_blank" href="/files/app_files/' . $key->file2 . '">Ek2</a>';
//                if ($key->file3 != null)
//                    $string .= ' <a target="_blank" href="/files/app_files/' . $key->file3 . '">Ek3</a>';
//                return $string;
//            })
//            ->editColumn('confirm', function ($key) {
//                if ($key->confirm == '0')
//                    return '<b class="text-primary">Onay Bekliyor</b>';
//                else if ($key->confirm == '1')
//                    return '<b class="text-success">Onaylandı!</b>';
//                else if ($key->confirm == '-1')
//                    return '<b style="text-decoration: underline;" title="' . $key->reject_reason . '" class="cursor-pointer text-danger">Reddedildi!</b>';
//            })
//            ->editColumn('paid', function ($key) {
//                return '<b class="text-primary">' . getDotter($key->paid) . '₺</b>';
//            })
//            ->editColumn('confirming_user_name_surname', function ($key) {
//                if ($key->confirming_user_name_surname != null)
//                    return $key->confirming_user_name_surname . ' (' . $key->confirming_user_display_name . ')';
//            })
//            ->editColumn('confirm_paid', function ($key) {
//                return $key->confirm_paid != '' ? '<b class="text-danger">' . getDotter($key->confirm_paid) . '₺</b>' : ' ';
//            })
//            ->editColumn('delete', function ($key) {
//                return '<span style="text-decoration: underline" class="ml-3 text-primary font-weight-bold cursor-pointer text-center details-app" id="' . $key->id . '">Detay</span>';
//            })
            ->rawColumns(['description', 'add_files', 'confirm', 'paid', 'confirm_paid', 'delete'])
            ->make(true);
    }

}
