<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPayment;
use App\Models\AgencyPaymentApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class PaymentAppSetConfirmSuccessAction
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


        if (getDoubleValue($request->paid) == 0)
            return response()
                ->json(['status' => 0, 'message' => 'Onaylanan tutar 0 olamaz!'], 200);


        DB::beginTransaction();

        try {
            $update = AgencyPaymentApp::find($appID)
                ->update([
                    'confirm' => '1',
                    'confirming_user_id' => Auth::id(),
                    'reject_reason' => '',
                    'confirm_paid' => getDoubleValue($request->paid),
                    'confirming_date' => Carbon::now(),
                ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => 0, 'message' => 'Başvuru onayı esnasında hata oluştu, lütfen daha sonra tekrar deneyin!'], 200);
        }


        try {

            $control = AgencyPayment::where('app_id', $appID)->first();

            $app = AgencyPaymentApp::find($appID);

            if ($control == null)
                $insert = AgencyPayment::create([
                    'row_type' => 'BAŞVURU',
                    'app_id' => $appID,
                    'user_id' => Auth::id(),
                    'agency_id' => $app->agency_id,
                    'description' => $appID . ' numaralı başvuru üzerinde ödeme kayıt edilmiştir.',
                    'payment_channel' => $app->payment_channel,
                    'payment_date' => $app->created_at,
                    'payment' => $app->confirm_paid,
                ]);
            else
                $update = AgencyPayment::where('app_id', $appID)
                    ->update([
                        'row_type' => 'BAŞVURU',
                        'app_id' => $appID,
                        'user_id' => Auth::id(),
                        'description' => $appID . ' numaralı başvuru üzerinde ödeme kayıt edilmiştir.',
                        'payment_channel' => $app->payment_channel,
                        'payment_date' => $app->created_at,
                        'payment' => $app->confirm_paid,
                    ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => 0, 'message' => 'Ödeme kayıt işlemi esnasında bir hata oluştu, lütfen daha sonra tekrar deneyin!'], 200);
        }


        GeneralLog($appID . " Başvuru numaralı ödeme başvurusu onaylandı olarak güncellendi!");
        DB::commit();
        return response()
            ->json(['status' => 1, 'message' => 'İşlem başarılı, başvuru Onaylandı olarak güncellendi!'], 200);


    }
}
