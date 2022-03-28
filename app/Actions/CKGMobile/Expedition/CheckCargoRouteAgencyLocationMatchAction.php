<?php

namespace App\Actions\CKGMobile\Expedition;

use App\Models\Agencies;
use App\Models\Cargoes;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckCargoRouteAgencyLocationMatchAction
{
    use AsAction;

    public function handle($agency, $cargo)
    {
        $locations = $agency->localLocations;

        $checkLocation = $locations->filter(function ($item) use($cargo) {
            return ($item->city == $cargo->receiver_city && $item->district == $cargo->receiver_district && $item->neighborhood == $cargo->receiver_neighborhood);
        })->first();

        if ($checkLocation) return true;

        return false;
    }
}
