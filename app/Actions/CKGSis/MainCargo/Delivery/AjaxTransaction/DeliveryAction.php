<?php

namespace App\Actions\CKGSis\MainCargo\Delivery\AjaxTransaction;

use App\Models\Agencies;
use App\Models\Cargoes;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class DeliveryAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'transaction' => 'required',
            'receiverNameSurnameCompany' => 'required',
            'receiverTCKN' => 'required',
            'receiverProximity' => 'required',
            'deliveryDate' => 'required',
            'cargoId' => 'required',
            'descriptionDelivery' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ['status' => '0', 'errors' => $validator->getMessageBag()->toArray()];


        $cargo = Cargoes::find($request->cargoId);
        if ($cargo == null)
            return ['status' => -1, 'message' => 'Kargo bulunamadı!'];

        if ($cargo->transporter != 'CK')
            return ['status' => -1, 'message' => 'Taşıyıcısı sadece Cumhuriyet Kargo olan kargolara teslimat girebilirsiniz!'];


        if (Auth::user()->agency_code != $cargo->arrival_agency_code)
            return ['status' => -1, 'message' => 'Kargonun varış şubesi siz olmadığınızdan bu kargoya teslimat giremezsiniz!'];


        return $request;
    }
}
