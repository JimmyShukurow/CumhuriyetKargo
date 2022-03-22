<?php

namespace App\Actions\CKGMobile\CargoBagTransactions;

use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\CargoMovements;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterMovementAction
{
    use AsAction;

    public function handle($ctn, $bag, $cargoID, $authID, $reversePartQuantity, $groupID, $movement_key, $importance, $deleted_from = null)
    {
        # Get Movement Text
        $info = DB::table('cargo_movement_contents')
            ->where('key', $movement_key)
            ->first();

        if (Auth::user()->user_type == 'Acente')
            $branch = Agencies::where('id', Auth::user()->agency_code)->first();
        else
            $branch = TransshipmentCenters::find(Auth::user()->tc_code);

        if (Auth::user()->user_type == 'Acente') $infoText = str_replace(['[branch]'], [$branch->city . ' - ' . $branch->district . ' - ' . $branch->agency_name . ' ŞUBESI'], $info->content);
        elseif (Auth::user()->user_type == 'Aktarma') $infoText = str_replace(['[branch]'], [$branch->city . ' - ' . $branch->district . ' - ' . $branch->agency_name . ' TRM.'], $info->content);

        $infoText = str_replace(['[bag_tracking_no]'], [$bag->tracking_no], $infoText);
        $infoText = str_replace(['[bag_type]'], [$bag->type], $infoText);
        if ($deleted_from != null && $movement_key == 'delete_from_cargo_bag') $infoText = str_replace(['[deleted_from]'], [strtoupper($deleted_from)], $infoText);

        $movement = CargoMovements::where('ctn', $ctn)->orderBy('created_at', 'asc')->first();
        $cargo = Cargoes::find($cargoID);

        $variable = null;

        if (Auth::id() == $movement->user->id && Auth::user()->user_type == 'Acente' && $movement->user->user_type == 'Acente') $variable = 'ÇIKIŞ ŞUBESİNDE';
        elseif (Auth::user()->user_type == 'Acente' && Auth::user()->agency_code == $cargo->arrival_agency_code) $variable = 'VARIŞ ŞUBESİNDE';
        elseif (Auth::user()->user_type == 'Aktarma' && Auth::user()->tc_code == $cargo->departure_tc_code) $variable = 'ÇIKIŞ TRANSFER MERKEZİNDE';
        elseif (Auth::user()->user_type == 'Aktarma' && Auth::user()->tc_code == $cargo->arrival_tc_code) $variable = 'VARIŞ TRANSFER MERKEZİNDE';
        elseif (Auth::user()->user_type == 'Aktarma' && Auth::user()->tc_code != $cargo->departure_tc_code && Auth::user()->tc_code != $cargo->arrival_tc_code) $variable = 'TRANSFER MERKEZİNDE';

        InsertCargoMovement($ctn, $cargoID, $authID, $reversePartQuantity, $infoText, $variable, $groupID, $importance);

    }
}
