<?php

namespace App\Actions\CKGSis\Customer\AjaxTransaction;

use Lorisleiva\Actions\Concerns\AsAction;

class ConfirmCurrentWithVKNAction
{
    use AsAction;

    public function handle($request)
    {

        if ($request->vkn == '')
            return response()
                ->json(['status' => 0, 'message' => 'Vergi kimilk no alanı zorunludur!'], 200);

        if ($request->city == '')
            return response()
                ->json(['status' => 0, 'message' => 'Vergi dairesi şehir alanı zorunludur!'], 200);

        if ($request->taxOffice == '')
            return response()
                ->json(['status' => 0, 'message' => 'Vergi dairesi alanı zorunludur!'], 200);


        return vkn_confirm($request->vkn, $request->taxOffice, $request->city);
    }
}
