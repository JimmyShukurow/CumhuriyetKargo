<?php

namespace App\Actions\CKGSis\Expedition;

use App\Models\ExpeditionMovement;
use Lorisleiva\Actions\Concerns\AsAction;

class ExpeditionMovementAction
{
    use AsAction;

    public function handle($expedition_id, $user_id, $description)
    {
        return ExpeditionMovement::create([
            'expedition_id' => $expedition_id,
            'user_id' => $user_id,
            'description' => $description
        ]);
    }
}
