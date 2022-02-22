<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPayment;
use App\Models\AgencyPaymentApp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class PaymentAppSetConfirmWaitingAction
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


        DB::beginTransaction();

        try {
            $update = AgencyPaymentApp::find($appID)
                ->update([
                    'confirm' => '0',
                    'confirming_user_id' => Auth::id(),
                    'reject_reason' => '',
                    'confirming_date' => null,
                    'confirm_paid' => null,
                ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => 0, 'message' => 'Başvuru güncelleme ensasında hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }

        try {
            $app = AgencyPaymentApp::find($appID);

            $controlPayment = AgencyPayment::where('app_id', $app->id)->first();

            if ($controlPayment != null)
                $delete = AgencyPayment::find($controlPayment->id)
                    ->delete();

        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => 0, 'message' => 'Ödeme güncelleme ensasında hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }

        DB::commit();

        GeneralLog($appID . " Başvuru numaralı ödeme başvurusu Onay Bekliyor olarak güncellendi!");
        return response()
            ->json(['status' => 1, 'message' => 'İşlem başarılı, başvuru Onay Bekliyor olarak güncellendi!'], 200);


    }
}
