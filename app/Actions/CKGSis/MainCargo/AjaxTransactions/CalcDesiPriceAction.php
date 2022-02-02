<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Cities;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CalcDesiPriceAction
{
    use AsAction;

    public function handle($request)
    {
        $desi = $request->desi;
                $distance = 0;
                if ($desi == 0)
                    return response()->json(['status' => '-1', 'message' => 'Ücrete Esas Ağırlık (Desi) 0\'dan büyük olmalıdır!']);

                if ($desi > 0 && $desi < 1)
                    $desi = 1;

                $startPoint = tr_strtoupper($request->startPoint);
                $endPoint = tr_strtoupper($request->endPoint);
                if ($startPoint == $endPoint)
                    $distancePrice = 0;
                else {
                    ## get plaque
                    $startPoint = Cities::where('city_name', $startPoint)->first('plaque');
                    $endPoint = Cities::where('city_name', $endPoint)->first('plaque');#
                    $json = json_decode(distances(), true);
                    $distance = $json[$startPoint->plaque][$endPoint->plaque];
                    # => calculate distance price <= #
                    $distancePrice = calcDistancePrice($distance);
                }

                ## calc desi price
                $maxDesiInterval = DB::table('desi_lists')
                    ->orderBy('finish_desi', 'desc')
                    ->first();
                $maxDesiPrice = $maxDesiInterval->individual_unit_price;
                $maxDesiInterval = $maxDesiInterval->finish_desi;

                $desiPrice = 0;
                if ($desi > $maxDesiInterval) {
                    $desiPrice = $maxDesiPrice;

                    $amountOfIncrease = DB::table('settings')->where('key', 'desi_amount_of_increase')->first();
                    $amountOfIncrease = $amountOfIncrease->value;

                    for ($i = $maxDesiInterval; $i < $desi; $i++)
                        $desiPrice += $amountOfIncrease;
                } else {
                    #catch interval
                    $desiPrice = DB::table('desi_lists')
                        ->where('start_desi', '<=', $desi)
                        ->where('finish_desi', '>=', $desi)
                        ->first();
                    $desiPrice = $desiPrice->individual_unit_price;
                }

                return response()->json(['status' => '1', 'price' => $desiPrice, 'distance' => $distance, 'distance_price' => $distancePrice, 'post_service_price' => $postServicePrice]);
    }
}
