<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\Agencies;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class ChangeAgencySafeStatusAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'id' => 'required',
            'status' => 'required|in:1,0',
            'description' => 'nullable'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => 0, 'errors' => $validator->getMessageBag()->toArray()], 200);

        $agency = Agencies::find($request->id);

        if ($agency == null)
            return response()
                ->json(['status' => -1, 'message' => 'Şube bulunamdı!'], 200);

        if ($agency->safe_status == '1' && $request->status == '1')
            return response()
                ->json(['status' => -1, 'message' => 'Kasa zaten aktif!'], 200);

        if ($agency->safe_status == '0' && $request->status == '0')
            return response()
                ->json(['status' => -1, 'message' => 'Kasa zaten pasif!'], 200);

        $message = $request->description != null ? $request->description : "Acente kasanız muhasebe birimi tarafından kapatılmıştır.";

        if ($request->status == '1')
            $message = null;

        $update = Agencies::find($request->id)
            ->update([
                'safe_status' => $request->status,
                'safe_status_description' => $message,
            ]);

        if ($update) {

            $statusString = $request->status == '1' ? 'Aktif' : 'Pasif';

            $arr = [
                'Kasa Durumu' => $statusString,
                'Acente Adı' => $agency->agency_name,
                'Kasa Durumu Açıklaması' => $message,
                'Tarih' => Carbon::now()
            ];

            GeneralLog('Acente Kasası ' . $statusString . ' edildi!', $arr);
            return response()
                ->json(['status' => 1, 'message' => 'İşlem başarılı, güncelleme gerçekleştirildi!'], 200);
        } else
            return response()
                ->json(['status' => -1, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);

    }
}
