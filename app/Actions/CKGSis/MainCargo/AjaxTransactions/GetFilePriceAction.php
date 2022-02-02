<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Cities;
use App\Models\FilePrice;
use Lorisleiva\Actions\Concerns\AsAction;

class GetFilePriceAction
{
    use AsAction;

    public function handle($request)
    {
        $distance = 0;
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

                $filePrice = FilePrice::first();
                $filePrice = $filePrice->individual_file_price;

                return response()->json(['status' => '1', 'price' => $filePrice, 'distance' => $distance, 'distance_price' => $distancePrice]);
    }
}
