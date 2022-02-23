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


        $rows = DB::table('view_agency_payment_app_details')
            ->whereRaw($dateFilter == 'true' ? "created_at between '" . $firstDate . " 00:00:00'  and '" . $lastDate . " 23:59:59'" : ' 1 > 0')
            ->where('agency_id', '=', Auth::user()->agency_code);


        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('add_files', function ($key) {
                $string = "";
                if ($key->file1 != null)
                    $string = '<a target="_blank" href="/files/app_files/' . $key->file1 . '">Ek1</a>';
                if ($key->file2 != null)
                    $string .= ' <a target="_blank" href="/files/app_files/' . $key->file2 . '">Ek2</a>';
                if ($key->file3 != null)
                    $string .= ' <a target="_blank" href="/files/app_files/' . $key->file3 . '">Ek3</a>';
                return $string;
            })
            ->editColumn('confirm', function ($key) {
                if ($key->confirm == '0')
                    return '<b class="text-primary">Onay Bekliyor</b>';
                else if ($key->confirm == '1')
                    return '<b class="text-success">Onaylandı!</b>';
                else if ($key->confirm == '-1')
                    return '<b style="text-decoration: underline;" title="' . $key->reject_reason . '" class="cursor-pointer text-danger">Reddedildi!</b>';
            })
            ->editColumn('paid', function ($key) {
                return '<b class="text-primary">' . getDotter($key->paid) . '₺</b>';
            })
            ->editColumn('confirming_user_name_surname', function ($key) {
                if ($key->confirming_user_name_surname != null)
                    return $key->confirming_user_name_surname . ' (' . $key->confirming_user_display_name . ')';
            })
            ->editColumn('confirm_paid', function ($key) {
                return $key->confirm_paid != '' ? '<b class="text-danger">' . getDotter($key->confirm_paid) . '₺</b>' : ' ';
            })
            ->editColumn('delete', function ($key) {
                return '<span style="text-decoration: underline" class="text-danger font-weight-bold cursor-pointer text-center delete-app" id="' . $key->id . '">Başvuruyu Sil</span>';
            })
            ->rawColumns(['description', 'add_files', 'confirm', 'paid', 'confirm_paid', 'delete'])
            ->make(true);
    }

}
