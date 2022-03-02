<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPaymentApp;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgencyPaymentAppsSummeryAction
{
    use AsAction;

    public function handle()
    {
        $data['all'] = AgencyPaymentApp::all()->count();
        $data['success'] = AgencyPaymentApp::all()->where('confirm', '1')->count();
        $data['waiting'] = AgencyPaymentApp::all()->where('confirm', '0')->count();
        $data['reject'] = AgencyPaymentApp::all()->where('confirm', '-1')->count();

        return response()
            ->json(['status' => 1, 'data' => $data], 200);

    }
}
