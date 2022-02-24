<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPaymentApp;
use http\Env\Response;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgencyPaymentAppDetails
{
    use AsAction;

    public function handle($request)
    {
        $appID = $request->id;

        if ($appID == null)
            return response()
                ->json(['status' => 0, 'message' => 'Başvuru No alanı gereklidir!'], 200);


        $app = DB::table('view_agency_payment_app_details')
            ->where('id', $appID)
            ->first();

        if ($app == null)
            return response()
                ->json(['status' => 0, 'message' => 'Başvuru bulunamadı!'], 200);

        $app->paid = getDotter($app->paid);

        return response()
            ->json(['status' => 1, 'data' => $app], 200);
    }
}
