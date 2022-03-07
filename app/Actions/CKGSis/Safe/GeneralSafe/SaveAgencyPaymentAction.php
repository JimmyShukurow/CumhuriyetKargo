<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\AgencyPayment;
use App\Rules\AgencyControl;
use App\Rules\PriceControl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveAgencyPaymentAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'payment' => ['required', new PriceControl],
            'agencyID' => ['required', new AgencyControl],
            'payingNameSurname' => 'required',
            'paymentChannel' => 'required',
            'paymentDate' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

        try {
            $insert = AgencyPayment::create([
                'row_type' => 'ONAYLI',
                'user_id' => Auth::id(),
                'description' => $request->description == null ? 'ŞUBE KASA ÖDEMESİ' : tr_strtoupper($request->description),
                'agency_id' => $request->agencyID,
                'payment' => getDoubleValue($request->payment),
                'payment_channel' => $request->paymentChannel,
                'paying_name_surname' => tr_strtoupper($request->payingNameSurname),
                'payment_date' => $request->paymentDate
            ]);
        } catch (Exception $e) {
            return response(['status' => -1, 'exeption' => $e->getMessage(), 'message' => 'Ödeme kaydı ensasında hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }


        GeneralLog('Acente ödemesi girildi!');
        return response(['status' => 1, 'message' => 'Ödeme işlemi başarıyla kayıt edildi!'], 200);

    }
}
