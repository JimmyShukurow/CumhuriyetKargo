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

        $plaka = $request->plaka;
        $serialNo = $request->serialNo;
        $departureBranch = $request->departureBranch;
        $arrivalBranch = $request->arrivalBranch;
        $creator = $request->creator;
        $doneStatus = $request->doneStatus;

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


        $rows = Expedition::with(
            [
                'car:id,plaka',
                'user:users.id,name_surname,display_name',
                'routes.branch',
            ])
            ->when($doneStatus, function ($q) use ($doneStatus) {
                return $q->where('done', $doneStatus);
            })
            ->when($serialNo, function ($q) use ($serialNo) {
                return $q->where('serial_no', str_replace(' ', '', $serialNo));
            })
            ->when($plaka, function ($q) use ($plaka) {
                return $q->whereHas('car', function ($query) use ($plaka) {
                    $query->where('plaka', 'like', '%' . $plaka . '%');
                });
            })
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->get();

        $rows->each(function ($key) {
            $key['departure_branch'] = $key->routes->where('route_type', 1)->first();

            if ($key['departure_branch']->branch_type == 'Acente')
                $key['departure_branch'] = $key->departure_branch->branch->agency_name . ' ŞUBE';
            else if ($key['departure_branch']->branch_type == 'Aktarma')
                $key['departure_branch'] = $key->departure_branch->branch->tc_name . ' TRM.';

            $key['arrival_branch'] = $key->routes->where('route_type', -1)->first();

            if ($key['arrival_branch']->branch_type == 'Acente')
                $key['arrival_branch'] = $key->arrival_branch->branch->agency_name . ' ŞUBE';
            else if ($key['arrival_branch']->branch_type == 'Aktarma')
                $key['arrival_branch'] = $key->arrival_branch->branch->tc_name . ' TRM.';

            $key['route_count'] = $key->routes->count() - 2;
        });


        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('plaka', function ($key) {
                return $key->car->plaka;
            })
            ->editColumn('serial_no', function ($key) {
                return '<b style="text-decoration: underline; cursor: pointer;">' . CurrentCodeDesign($key->serial_no) . '</b>';
            })
            ->editColumn('name_surname', function ($key) {
                return $key->user->name_surname . ' (' . $key->user->display_name . ')';
            })
            ->editColumn('created_at', function ($key) {
                return $key->created_at;
            })
            ->editColumn('status', function ($key) {
                return $key->done == '0' ? '<b class="text-success">Devam Ediyor</b>' : '<b class="text-danger">Sefer Bitti!</b>';
            })
            ->rawColumns(['description', 'status', 'add_files', 'serial_no'])
            ->make(true);
    }
}
