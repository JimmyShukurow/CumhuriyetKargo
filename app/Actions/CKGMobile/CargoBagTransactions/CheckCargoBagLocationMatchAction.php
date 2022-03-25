<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Models\CargoBags;
use App\Models\Cargoes;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckCargoBagLocationMatchAction
{
    use AsAction;

    public function handle(CargoBags $bag, Cargoes $cargo)
    {
        $locations = $bag->agency->localLocations;
        $checkLocation = $locations->filter(function ($item) use($cargo) {
            return ($item->city == $cargo->receiver_city && $item->district == $cargo->receiver_district && $item->neighborhood == $cargo->receiver_neighborhood);
        })->first();

        if ($checkLocation) return true;

        return false;

    }
}
