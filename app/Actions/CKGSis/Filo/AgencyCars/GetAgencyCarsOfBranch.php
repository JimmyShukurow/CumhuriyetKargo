<?php

namespace App\Actions\CKGSis\Filo\AgencyCars;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class GetAgencyCarsOfBranch
{
    use AsAction;

    public function handle($request)
    {
        $tc = TransshipmentCenters::find(Auth::user()->tc_code);
        $ids= collect();

        $districts = $tc->districts()->with('agencies')->whereHas('agencies')->get();
        $districts = $districts->map( function($q) use ($ids){
            $agencies = $q->agencies;
            return  $agencies->map(function($query) use ($ids){ $ids->push($query->id); return $query->id;});
        });

        $marka = $request->marka;
        $model = $request->model;
        $plaka = $request->plaka;
        $soforAd = $request->soforAd;
        $creator = $request->creator;
        $confirm = $request -> confirmation;

        $cars = TcCars::with('creator', 'cikishAktarma', 'varishAktarma')
            ->where('car_type', 'Acente')
            ->when($marka, function($q) use($marka){ return $q->where('marka', 'like', '%'.$marka.'%');})
            ->when($model, function($q) use($model){ return $q->where('model', 'like', '%'.$model.'%');})
            ->when($plaka, function($q) use($plaka){ return $q->where('plaka', 'like', '%'.$plaka.'%');})
            ->when($soforAd, function($q) use($soforAd){ return $q->where('sofor_ad', 'like', '%'.$soforAd.'%');})
            ->when($confirm, function($q) use($confirm){ return $q->where('confirm', $confirm);})
            ->when($creator, function($q) use($creator){ 
                return $q->whereHas('creator', function($query) use ($creator){$query->where('name_surname', 'like', '%'.$creator.'%');});
            })
            ->orderBy('created_at', 'DESC')
            ->whereIn('branch_code', $ids)
            ->get();

        $cars->each(function($key){ 
            $key['branch'] = $key->branch->agency_name ?? null; 
            $key['creator'] = $key->creator->name_surname ?? null; 
            $key['cikis_aktarma'] = $key->cikishAktarma->tc_name ?? null; 
            $key['varis_aktarma'] = $key->varishAktarma->tc_name ?? null; 
        });

        // This part is Yajra Datatables, you can google it to research ...
        return DataTables::of($cars)
            ->setRowId(function ($cars) {
                return 'car-item-' . $cars->id;
            })
            ->editColumn('branch', function ($cars) {
                return '<b class="text-success">' . $cars->branch . '</b>';
            })
            ->editColumn('creator', function ($cars) {
                return '<b class="text-success">' . $cars->creator . '</b>';
            })
            ->editColumn('confirmation_status', function ($cars) {

                if($cars->confirm == 0) return '<b class="text-warning"> Onay Bekliyor </b>';

                else if($cars->confirm == 1) return '<b class="text-success"> Onaylandı </b>';

                else if($cars->confirm == -1) return '<b class="text-danger"> Onaylanmadi </b>';
            })
          
            ->addColumn('details', 'backend.operation.tc_cars.column')
            ->rawColumns(['details', 'branch', 'creator', 'confirmation_status'])
            ->make(true);
    }
}
