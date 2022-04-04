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
        $allCargoes = Cargoes::all();
        $cargoes = $allCargoes->whereBetween('created_at', [$firstDate, $lastDate]);
        $cargoes->all();

        $data['endorsementCurrentDate'] = $cargoes->sum('total_price');

        $data['totalCargosCurrentDate'] =$cargoes->count();

        $data['cargoCountCurrentDate'] = $cargoes->whereNotIn('cargo_type', ['Mi', 'Dosya'])->count();

        $data['fileCountCurrentDate'] =$cargoes->whereIn('cargo_type', ['Mi', 'Dosya'])->count();

        $data['totalDesiCurrentDate'] = $cargoes->sum('desi');

        $data['endorsementAllTime'] = $allCargoes->sum('total_price');

        $data['inSafeAllTime'] = AgencyPayment::sum('payment');

        foreach ($data as $key => $val) {
            $data[$key] = getDotter($data[$key]);
        }

        $rds = RegioanalDirectorates::with([
            'districts'=> function($q) use ($firstDate, $lastDate) {
                $q->with(['agencies' =>function($query) use ($firstDate, $lastDate) {
                    $query->with(['relatedCargoes' =>function($cargo) use ($firstDate, $lastDate) {
                        $cargo->whereBetween('created_at', [$firstDate, $lastDate]);}
                ]);}
                ])->whereHas('agencies');}

        ])->get();

        $data['regions'] = $rds->pluck('name');

        $regionCargoCount = collect();
        $tmpCollectionCargoCount = collect();

        $regionEndorsements = collect();
        $tmpCollection = collect();
        $tmpSum = 0;
        $agencies = $rds->map(function ($q) use ($regionEndorsements, $tmpCollection, $firstDate, $lastDate, $tmpCollectionCargoCount) {
            $districts = $q->districts;
            $regionEndorsements = collect();
            $regionCargoCount = collect();
            $districts = $districts->map(function ($query) use ($regionEndorsements, $firstDate, $lastDate, $regionCargoCount) {
                $agency = $query->agencies;
                $agency = $agency->map(function ($keyAgency) use ($regionEndorsements, $firstDate, $lastDate, $regionCargoCount) {
                    $regionEndorsements->push($keyAgency->relatedCargoes->sum('total_price'));
                    $regionCargoCount->push($keyAgency->relatedCargoes->count());
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


        $agencies = Agencies::with(['relatedCargoes'=>function($q) use ($firstDate, $lastDate) {$q->whereBetween('created_at', [$firstDate, $lastDate]);}])->get();
        $agencies->map(function ($q) {
            $q->endorsement = round($q->relatedCargoes->sum('total_price'), 2);
            $q->cargo_count = $q->relatedCargoes->count();
            $q->cargo_cargo_count = $q->relatedCargoes->whereNotIn('cargo_type', ['Dosya', 'Mi'])->count();
            $q->cargo_desi_amount = round($q->relatedCargoes->sum('desi'), 2);
            $q->cargo_file_count = $q->relatedCargoes->whereIn('cargo_type', ['Dosya', 'Mi'])->count();
            $q->personel_count = $q->personelCount();
            $q->region = $q->region()->first()->name;
        });
        $data['agencies'] = $agencies->sortByDesc('endorsement')->values();

        return $data;
    }
}
