<?php

namespace App\Actions\CKGMobile\Expedition;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CargoExpeditionMovementAction
{
    use AsAction;

    public function handle($ctn, $cargo,  $authID, $partNo, $groupID, $importance, $movement_key, $plaque)
    {
        # Get Movement Text
        $info = DB::table('cargo_movement_contents')
            ->where('key', $movement_key)
            ->first();
        $branch = getUserBranchInfo();
        $infoText = str_replace(['[branch]'], [$branch['name'] . ' ' . $branch['type']], $info->content);
        $infoText = str_replace(['[PLAKA]'], [$plaque], $infoText);

        $status = 'test';

//        switch ()

        InsertCargoMovement($ctn, $cargo->id, $authID, $partNo, $infoText, $status, $groupID, $importance);

    }
}
