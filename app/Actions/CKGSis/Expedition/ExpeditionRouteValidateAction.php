<?php

namespace App\Actions\CKGSis\Expedition;

use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use Lorisleiva\Actions\Concerns\AsAction;

class ExpeditionRouteValidateAction
{
    use AsAction;

    public function handle($expeditionRoutes)
    {
        $response['status'] = 1;

        if (is_array($expeditionRoutes)) {
            foreach ($expeditionRoutes as $key) {
                if ($key[0] == 'Acente') {

                    $agency = Agencies::find($key[1]);

                    if ($agency == null)
                        return response()
                            ->json(['status' => -1, 'message' => 'Acente bulunamadı, lütfen sayfayı yenileyip tekrar deneyin!'], 200);

                    if ($agency->operation_status == '0') {
                        $response['status'] = -1;
                        $response['message'] = $agency->agency_name . " ŞUBE'nin operasyon statüsü pasif olduğundan bu acenteye araç çıkaramazsınız. Lütfen güzergahtan kaldırıp tekrar deneyiniz.";
                    }

                } else if ($key[0] == 'Aktarma') {

                    $tc = TransshipmentCenters::find($key[1]);
                    if ($tc == null) {
                        $response['status'] = -1;
                        $response['message'] = 'Transfer Merkezi bulunamadı, lütfen sayfayı yenileyip tekrar deneyin!';
                    }
                }
            }
        }

        return $response;
    }
}
