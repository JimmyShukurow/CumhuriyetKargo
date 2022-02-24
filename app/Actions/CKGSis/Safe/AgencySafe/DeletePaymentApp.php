<?php

namespace App\Actions\CKGSis\Safe\AgencySafe;

use App\Models\AgencyPaymentApp;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class DeletePaymentApp
{
    use AsAction;

    public function handle($request)
    {
        $destroyID = $request->destroy_id;

        $app = AgencyPaymentApp::find($destroyID);

        if ($app == null || Auth::user()->agency_code != $app->agency_id)
            return response()
                ->json(['status' => 0, 'message' => 'Başvuru bulunamadı!']);


        $arr = collect([1, 20]);
        if (!$arr->contains(Auth::user()->role_id))
            return response()
                ->json(['status' => 0, 'message' => 'Silme işlemini sadece Acente Müdürleri ve yetkililer yapabilir!']);

        if ($app->confirm != '0')
            return response()
                ->json(['status' => 0, 'message' => 'İşlem görmüş başvuruyu silemezsiniz!']);

        $app = AgencyPaymentApp::find($destroyID)
            ->delete();

        if ($app) {
            GeneralLog('Ödeme bildirgesi silindi!');
            return response()
                ->json(['status' => 1, 'message' => 'İşlem başarılı, başvuru silindi!']);
        } else
            return response()
                ->json(['status' => 0, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz.']);

    }
}
