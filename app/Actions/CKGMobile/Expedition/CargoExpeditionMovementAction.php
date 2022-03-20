<?php

namespace App\Actions\CKGMobile\Expedition;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CargoExpeditionMovementAction
{
    use AsAction;

    public function handle($ctn, $cargo, $authID, $partNo, $groupID, $importance, $movement_key, $plaque)
    {
        # Get Movement Text
        $info = DB::table('cargo_movement_contents')
            ->where('key', $movement_key)
            ->first();
        $branch = getUserBranchInfo();

        $infoText = str_replace(['[branch]'], [$branch['name'] . ' ' . $branch['type']], $info->content);
        $infoText = str_replace(['[PLAKA]'], [$plaque], $infoText);

        $status = 'test';

        $branchType = $branch['type2'];
        $branchID = $branch['id'];

        if ($branchType == 'Acente') {
            if ($cargo->departure_agency_code == $branchID)
                $status = 'ÇIKIŞ ŞUBESİNDE';
            else if ($cargo->arrival_agency_code == $branchID)
                $status = 'VARIŞ ŞUBESİNDE';
            else
                $status = 'ŞUBEDE';
        } else if ($branchType == 'Aktarma') {
            if ($cargo->departure_tc_code == $branchID)
                $status = 'ÇIKIŞ TRANSFER MERKEZİNDE';
            else if ($cargo->arrival_tc_code == $branchID)
                $status = 'VARIŞ TRANSFER MERKEZİNDE';
            else
                $status = 'TRANSFER MERKEZİNDE';
        }


        InsertCargoMovement($ctn, $cargo->id, $authID, $partNo, $infoText, $status, $groupID, $importance);

    }
}
