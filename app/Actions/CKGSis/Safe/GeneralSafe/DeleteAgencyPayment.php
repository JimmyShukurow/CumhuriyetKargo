<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPayment;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteAgencyPayment
{
    use AsAction;

    public function handle($request)
    {
        $id = $request->destroy_id;

        if ($id == null)
            return response()
                ->json(['status' => 0, 'message' => 'id alanı gereklidir'], 200);


        $delete = AgencyPayment::find($id);

        if ($delete == null)
            return response()
                ->json(['status' => 0, 'message' => 'Ödeme bulunamadı!'], 200);


        $delete = AgencyPayment::find($id)
            ->delete();

        GeneralLog('Acente ödemesi silindi!');
        return response()
            ->json(['status' => 1, 'message' => 'Silme işlemi başarıyla gerçekleşti!'], 200);


    }
}
