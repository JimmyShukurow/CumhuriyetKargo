<?php

namespace App\Actions\CKGSis\Dashboard\Gm\AjaxTransactions;

use App\Models\AgencyPayment;
use App\Models\Cargoes;
use App\Models\RegioanalDirectorates;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class GetSummeryAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);

        $diff = $firstDate->diffInDays($lastDate);
        if ($diff >= 120)
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

        $regionEndorsements = collect();
        $tmpCollection = collect();
        $tmpSum = 0;
        $agencies = $rds->map(function ($q) use ($regionEndorsements, $tmpCollection) {
            $districts = $q->districts()->with('agencies')->whereHas('agencies')->get();
            $districts = $districts->map(function ($query) use ($regionEndorsements, $tmpCollection) {
                $agency = $query->agencies;
                $agency->map(function ($keyAgency) use ($regionEndorsements) {
                    $regionEndorsements->push($keyAgency->endorsement());
                    return $keyAgency->with('endorsement');
                });
                return count($agency);
            });
            $tmpCollection->push($regionEndorsements->sum());
            $regionEndorsements = null;
            $regionEndorsements = collect();

            $tmpSum = 0;
            return $districts->sum();
        });
        $data['agencyCount'] = $agencies;
        $data['regionEndorsements'] = $tmpCollection;


        return response()
            ->json(['status' => 1, 'data' => $data], 200);


    }
}
