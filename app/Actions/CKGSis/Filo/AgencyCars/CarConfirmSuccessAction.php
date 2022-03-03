<?php

namespace App\Actions\CKGSis\Filo\AgencyCars;

use Lorisleiva\Actions\Concerns\AsAction;

class CarConfirmSuccessAction
{
    use AsAction;

    public function handle($request)
    {
        return $request->id;
    }
}
