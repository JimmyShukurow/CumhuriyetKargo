<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPayment;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPaymentInfoAction
{
    use AsAction;

    public function handle($request)
    {

        $payment = AgencyPayment::find($request->id);

        if ($payment == null)
            return response()
                ->json(['status' => 0, 'message' => 'Ödeme bulunamadı!'], 200);


        return response()
            ->json(['status' => 1, 'data' => $payment], 200);

    }
}
