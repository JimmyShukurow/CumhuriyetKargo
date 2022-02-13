<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Models\Agencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterMovementAction
{
    use AsAction;

    public function handle($ctn, $bag, $cargoID, $authID, $reversePartQuantity, $groupID, $movement_key, $deleted_from = null)
    {
        # Get Movement Text
        $info = DB::table('cargo_movement_contents')
            ->where('key', $movement_key)
            ->first();

        $agency = Agencies::where('id', Auth::user()->agency_code)->first();

        if (Auth::user()->user_type == 'Acente') $infoText = str_replace(['[branch]'], [$agency->city . ' - ' . $agency->district . ' - '. $agency->agency_name . ' ŞUBESI'], $info->content);
        elseif (Auth::user()->user_type == 'Aktarma') $infoText = str_replace(['[branch]'], [$agency->city . ' - ' . $agency->district . ' - '. $agency->agency_name . ' TRM.'], $info->content);

        $infoText = str_replace(['[bag_tracking_no]'], [$bag->tracking_no], $infoText );
        $infoText = str_replace(['[bag_type]'], [$bag->type], $infoText );
        if($deleted_from != null && $movement_key == 'delete_from_cargo_bag') $infoText = str_replace(['[deleted_from]'], [strtoupper($deleted_from)], $infoText );

        InsertCargoMovement($ctn, $cargoID, $authID, $reversePartQuantity, $infoText, $info->status, $groupID );

    }
}
