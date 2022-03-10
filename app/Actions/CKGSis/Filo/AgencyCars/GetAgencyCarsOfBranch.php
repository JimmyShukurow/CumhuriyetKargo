<?php

namespace App\Actions\CKGSis\Filo\AgencyCars;

use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;
use Yajra\DataTables\Facades\DataTables;

class GetAgencyCarsOfBranch
{
    use AsAction;

    public function handle($request)
    {
        $tc = TransshipmentCenters::find(Auth::user()->tc_code);
        $ids = collect();

        $districts = $tc->districts()->with('agencies')->whereHas('agencies')->get();
        $districts->map(
            function ($q) use ($ids) {
                $agencies = $q->agencies;
                $agencies->map(
                    function ($query) use ($ids) {
                        $ids->push($query->id);
                    }
                );
            }
        );

        $marka = $request->marka;
        $model = $request->model;
        $plaka = $request->plaka;
        $soforAd = $request->soforAd;
        $creator = $request->creator;
        $confirm = $request->confirmation;

        $cars = TcCars::with('branch', 'creator')
            ->where('car_type', 'Acente')
            ->when($marka, function ($q) use ($marka) {
                return $q->where('marka', 'like', '%' . $marka . '%');
            })
            ->when($model, function ($q) use ($model) {
                return $q->where('model', 'like', '%' . $model . '%');
            })
            ->when($plaka, function ($q) use ($plaka) {
                return $q->where('plaka', 'like', '%' . $plaka . '%');
            })
            ->when($soforAd, function ($q) use ($soforAd) {
                return $q->where('sofor_ad', 'like', '%' . $soforAd . '%');
            })
            ->when(($confirm != null), function ($q) use ($confirm) {
                return $q->where('confirm', $confirm);
            })
            ->when($creator, function ($q) use ($creator) {
                return $q->whereHas('creator', function ($query) use ($creator) {
                    $query->where('name_surname', 'like', '%' . $creator . '%');
                });
            })
            ->whereIn('branch_code', $ids)
            ->orderBy('created_at', 'DESC')
            ->get();

        $cars->each(function ($key) {
            $key['branch_name'] = $key->branch->agency_name ?? null;
            $creatorName = $key->creator->name_surname ?? null;
            $creatorRole = $key->creator->userRole->display_name ?? null;
            $key['creator_name'] = $creatorName.' ('.$creatorRole.')';
            $key['creator_agency'] = $key->creator->getAgency->agency_name ?? null;
        });
        // This part is Yajra Datatables, you can google it to research ...
        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
            })
            ->editColumn('branch', function ($cars) {
                return '<b class="text-success">' . $cars->branch_name . '</b>';
            })
            ->editColumn('creator', function ($cars) {
                return  $cars->creator_name ;
            })
            ->editColumn('creator_agency', function ($cars) {
                return  $cars->creator_agency ;
            })
            ->editColumn('confirmation_status', function ($cars) {

                if ($cars->confirm == 0) {
                    return '<b class="text-primary"> Onay Bekliyor </b>';
                } elseif ($cars->confirm == 1) {
                    return '<b class="text-success"> Onaylandı </b>';
                } elseif ($cars->confirm == -1) {
                    return '<b class="text-danger"> Reddedildi </b>';
                }
            })
            ->addColumn('car_status', function ($car){
                if ($car->status == 0) {
                    return '<b class="text-danger"> Pasif </b>';
                } elseif ($car->status == 1) {
                    return '<b class="text-success"> Aktif </b>';
                }
            })
            ->addColumn('details', 'backend.operation.tc_cars.column')
            ->rawColumns(['details', 'branch', 'creator', 'confirmation_status', 'creator_role', 'creator_agency', 'car_status'])
            ->make(true);
    }
}
