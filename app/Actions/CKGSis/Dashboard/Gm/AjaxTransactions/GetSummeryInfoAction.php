<?php

namespace App\Actions\CKGSis\Dashboard\Gm\AjaxTransactions;

use App\Models\Agencies;
use App\Models\AgencyPayment;
use App\Models\Cargoes;
use App\Models\RegioanalDirectorates;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSummeryInfoAction
{
    use AsAction;

    public function handle($request)
    {


        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);

        $diff = $firstDate->diffInDays($lastDate);
        if ($diff > 120)
            return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 120 gün olabilir!'], 509);

        $firstDate = substr($firstDate, 0, 10) . ' 00:00:00';
        $lastDate = substr($lastDate, 0, 10) . ' 23:59:59';


        $data['endorsementCurrentDate'] = Cargoes::all()
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->sum('total_price');

        $data['totalCargosCurrentDate'] = Cargoes::all()
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->count();

        $data['cargoCountCurrentDate'] = Cargoes::all()
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->whereNotIn('cargo_type', ['Mi', 'Dosya'])
            ->count();

        $data['fileCountCurrentDate'] = Cargoes::all()
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->whereIn('cargo_type', ['Mi', 'Dosya'])
            ->count();

        $data['totalDesiCurrentDate'] = Cargoes::all()
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->sum('desi');

        $data['endorsementAllTime'] = Cargoes::all()
            ->sum('total_price');

        $data['inSafeAllTime'] = AgencyPayment::all()
            ->sum('payment');

        foreach ($data as $key => $val) {
            $data[$key] = getDotter($data[$key]);
        }

        $rds = RegioanalDirectorates::all();
        $data['regions'] = $rds->pluck('name');

        $regionCargoCount = collect();
        $tmpCollectionCargoCount = collect();

        $regionEndorsements = collect();
        $tmpCollection = collect();
        $tmpSum = 0;
        $agencies = $rds->map(function ($q) use ($regionEndorsements, $tmpCollection, $firstDate, $lastDate, $tmpCollectionCargoCount) {
            $districts = $q->districts()->with('agencies')->whereHas('agencies')->get();
            $regionEndorsements = collect();
            $regionCargoCount = collect();
            $districts = $districts->map(function ($query) use ($regionEndorsements, $firstDate, $lastDate, $regionCargoCount) {
                $agency = $query->agencies;
                $agency = $agency->map(function ($keyAgency) use ($regionEndorsements, $firstDate, $lastDate, $regionCargoCount) {
                    $regionEndorsements->push($keyAgency->endorsementWithDate($firstDate, $lastDate));
                    $regionCargoCount->push($keyAgency->cargoCountWithDate($firstDate, $lastDate));
                    return $keyAgency->with('endorsement');
                });
                return count($agency);
            });
            $tmpCollection->push(round($regionEndorsements->sum(), 2));
            $tmpCollectionCargoCount->push($regionCargoCount->sum());
            $tmpSum = 0;
            return $districts->sum();
        });
        $data['agencyCount'] = $agencies;
        $data['regionEndorsements'] = $tmpCollection;
        $data['regionCargoCount'] = $tmpCollectionCargoCount;

        $tmpArray = collect();

        for ($i = 0, $iMax = count($data['regions']); $i < $iMax; $i++) {
            $tmpArray->push([
                'region' => $data['regions'][$i],
                'agencyCount' => $data['agencyCount'][$i],
                'cargoCount' => $data['regionCargoCount'][$i],
                'regionEndorsements' => $data['regionEndorsements'][$i]
            ]);
        }
        $data['data_full'] = $tmpArray;

        $tmpArray = collect($tmpArray);
        $tmpArray = $tmpArray->sortBy('regionEndorsements');

        $data['regions'] = $tmpArray->pluck('region');
        $data['regionCargoCount'] = $tmpArray->pluck('cargoCount');
        $data['regionEndorsements'] = $tmpArray->pluck('regionEndorsements');
        $data['agencyCount'] = $tmpArray->pluck('agencyCount');


        $agencies = Agencies::all();
        $agencies->map(function ($q) use ($firstDate, $lastDate) {
            $q->endorsement = $q->endorsementWithDate($firstDate, $lastDate);
            $q->cargo_count = $q->cargoCountWithDate($firstDate, $lastDate);
            $q->cargo_cargo_count = $q->cargoCargoCountWithDate($firstDate, $lastDate);
            $q->cargo_desi_amount = $q->cargoDsAmountWithDate($firstDate, $lastDate);
            $q->cargo_file_count = $q->cargoFileCountWithDate($firstDate, $lastDate);
            $q->personel_count = $q->personelCount();
            $q->region = $q->region()->first()->name;
        });
        $data['agencies'] = $agencies->sortByDesc('endorsement')->values();


        return $data;
    }
}
