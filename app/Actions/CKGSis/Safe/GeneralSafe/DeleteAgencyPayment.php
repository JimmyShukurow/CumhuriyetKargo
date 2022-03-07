<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPayment;
use App\Models\AgencyPaymentApp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();

        if ($delete->app_id != null) {
            try {
                $app = AgencyPaymentApp::find($delete->app_id)
                    ->update([
                        'confirm_paid' => 0,
                        'confirm' => '-1',
                        'reject_reason' => 'Bu ödeme ' . Auth::user()->name_surname . ' (' . Auth::user()->role->display_name . ') tarafından silindi.',
                        'confirming_user_id' => Auth::id(),
                        'confirming_date' => Carbon::now(),
                    ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()
                    ->json(['status' => 0, 'message' => 'Ödeme başvurusu güncellemesi esnasında hata oluştu!'], 200);
            }
        }

        try {
            $delete = AgencyPayment::find($id)
                ->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => 0, 'message' => 'Ödemenin silinmesi esnasında hata oluştu!'], 200);
        }

        GeneralLog('Acente ödemesi silindi!');
        DB::commit();
        return response()
            ->json(['status' => 1, 'message' => 'Silme işlemi başarıyla gerçekleşti!'], 200);


    }
}
