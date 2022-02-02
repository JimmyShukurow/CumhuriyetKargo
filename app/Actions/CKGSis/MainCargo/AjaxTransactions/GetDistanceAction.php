<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Cities;
use Lorisleiva\Actions\Concerns\AsAction;

class GetDistanceAction
{
    use AsAction;

    public function handle($request)
    {
        $startPoint = tr_strtoupper($request->startPoint);
                $endPoint = tr_strtoupper($request->endPoint);

                if ($startPoint == $endPoint)
                    $resposne = ['status' => 1, 'distance' => 0, 'price' => 0];
                else {
                    ## get plaque
                    $startPoint = Cities::where('city_name', $startPoint)->first('plaque');
                    $endPoint = Cities::where('city_name', $endPoint)->first('plaque');

                    $json = json_decode(distances(), true);
                    $distance = $json[$startPoint->plaque][$endPoint->plaque];

                    # => calculate distance price <= #
                    $distancePrice = calcDistancePrice($distance);

                    $resposne = ['status' => 1, 'distance' => getDotter($distance), 'price' => $distancePrice];
                }

                return response()->json($resposne, 200);
    }
}
