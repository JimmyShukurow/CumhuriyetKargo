<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction;

use App\Models\Currents;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class ConfirmCurrentAction
{
    use AsAction;

    public function handle($request)
    {

        $current = Currents::find($request->currentID);

        $PermRoleIDs = collect(GetCurrentConfirmerRoleIDs());
        if ($current->confirmed == '1')
            $jsonData = ['status' => -1, 'message' => 'Cari zaten onaylı!'];
        else if (!$PermRoleIDs->contains(Auth::user()->role_id))
            $jsonData = ['status' => -1, 'message' => 'Geçersiz Yetki!'];
        else if ($PermRoleIDs->contains(Auth::user()->role_id)) {

            $update = Currents::find($request->currentID)
                ->update([
                    'confirmed' => '1',
                    'confirmed_by_user_id' => Auth::id()
                ]);

            activity()
                ->inLog('Cari Onayı')
                ->performedOn($current)
                ->log($current->current_code . " kodlu cari hesap onaylandı!");

            if ($update)
                $jsonData = ['status' => 1];
        }

        return $jsonData;
    }
}
