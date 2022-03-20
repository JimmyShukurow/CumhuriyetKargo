<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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


        if (Auth::user()->user_type == 'Acente') {
            $ids = User::where('agency_code', Auth::user()->agency_code)->withTrashed()->get()->pluck('id');
        } else {
            $ids = User::where('tc_code', Auth::user()->tc_code)->withTrashed()->get()->pluck('id');
        }

        $expeditionIDs = collect();
        if ($departureBranch) {
            $rows_ids_Acente = FilterExpeditionAction::run(GetExpeditionActions::run($ids, $firstDate, $lastDate, $doneStatus, $serialNo, $plaka, $creator), $departureBranch, "Acente");
            $rows_ids_Aktarma = FilterExpeditionAction::run(GetExpeditionActions::run($ids, $firstDate, $lastDate, $doneStatus, $serialNo, $plaka, $creator), $departureBranch, "Aktarma");
            $expeditionIDs = $rows_ids_Acente->merge($rows_ids_Aktarma);
        }
        if ($arrivalBranch) {
            $rows_ids_Acente = FilterExpeditionArrivalAction::run(GetExpeditionActions::run($ids, $firstDate, $lastDate, $doneStatus, $serialNo, $plaka, $creator), $arrivalBranch, "Acente");
            $rows_ids_Aktarma = FilterExpeditionArrivalAction::run(GetExpeditionActions::run($ids, $firstDate, $lastDate, $doneStatus, $serialNo, $plaka, $creator), $arrivalBranch, "Aktarma");
            $expeditionIDs = $rows_ids_Acente->merge($rows_ids_Aktarma);
        }
        $rows = GetExpeditionActions::run($ids, $firstDate, $lastDate, $doneStatus, $serialNo, $plaka, $creator);
        if ($expeditionIDs->count() > 0) {
            $rows = $rows->whereIn('id', $expeditionIDs);
        }

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
            $key['cargo_count'] = $key->cargoes->count();
        });


        return datatables()->of($rows)
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 6, '...') . '</span>';
            })
            ->editColumn('plaka', function ($key) {
                return $key->car->plaka;
            })
            ->editColumn('serial_no', function ($key) {
                return '<a target="popup" onclick="window.open(\'/Expedition/Details/' . $key->id . '\',\'popup\',\'width=1500,height=1200\'); return false;" href="/Expedition/Details/' . $key->id . '"><b style="text-decoration: underline; cursor: pointer;"  class="expedition-details">' . CurrentCodeDesign($key->serial_no) . '</b></a>';

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
