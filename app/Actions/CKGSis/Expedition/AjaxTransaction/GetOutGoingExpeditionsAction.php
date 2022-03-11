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


        $rows = Expedition::with('car:id,plaka', 'user:users.id,name_surname,display_name')->get();


        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('plaka', function ($key) {
                return $key->car->plaka;
            })
            ->editColumn('name_surname', function ($key) {
                return $key->user->name_surname . ' (' . $key->user->display_name . ')';
            })
            ->editColumn('created_at', function ($key) {
                return $key->created_at;
            })
            ->editColumn('status', function ($key) {
                return $key->status == '0' ? '<b class="text-success">Devam Ediyor</b>' : '<b class="text-danger">Sefer Bitti!</b>';
            })
            ->rawColumns(['description', 'status', 'add_files', 'confirm', 'paid', 'confirm_paid', 'delete'])
            ->make(true);
    }
}
