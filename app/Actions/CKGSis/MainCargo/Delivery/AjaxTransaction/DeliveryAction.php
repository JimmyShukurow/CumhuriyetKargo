<?php

namespace App\Actions\CKGSis\MainCargo\Delivery\AjaxTransaction;

use App\Models\Agencies;
use App\Models\CargoAddServices;
use App\Models\Cargoes;
use App\Models\Currents;
use App\Models\SmsContent;
use App\Rules\CurrentNameControlRule;
use App\Rules\NameSurname\CurrentNameSurnameControlRule;
use Carbon\Carbon;
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
            'teslimAlanAdSoyad' => ['required', new CurrentNameSurnameControlRule()],
            'receiverTCKN' => 'required',
            'receiverProximity' => 'required',
            'deliveryDate' => 'required',
            'cargoId' => 'required',
            'descriptionDelivery' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ['status' => '0', 'errors' => $validator->getMessageBag()->toArray()];

        $deliveryDate = Carbon::parse($request->deliveryDate)->format('d/m/Y H:m');


        $cargo = Cargoes::find($request->cargoId);
        if ($cargo == null)
            return ['status' => -1, 'message' => 'Kargo bulunamadı!'];

        if ($cargo->transporter != 'CK' || $cargo->departure_agency_code != Auth::user()->agency_code)
            return ['status' => -1, 'message' => 'Taşıyıcısı sadece Cumhuriyet Kargo olan kargolara teslimat girebilirsiniz!'];

        if (Auth::user()->agency_code != $cargo->arrival_agency_code)
            return ['status' => -1, 'message' => 'Kargonun varış şubesi siz olmadığınızdan bu kargoya teslimat giremezsiniz!'];


        $current = Currents::find($cargo->sender_id);
        $receiver = Currents::find($cargo->receiver_id);

        $smstoCurrent = CargoAddServices::where('cargo_tracking_no', $cargo->tracking_no)
            ->where('service_name', 'Göndericiye SMS')->first();

        $smstoReceiver = CargoAddServices::where('cargo_tracking_no', $cargo->tracking_no)
            ->where('service_name', 'Alıcıya SMS')->first();

        if ($smstoCurrent != null) {
            $smsContent = SmsContent::where('key', 'cargo_delivery_current')->first();
            $sms = str_replace(
                [
                    '[name_surname]',
                    '[ctn]',
                    '[receiver_name]',
                    '[proximity]',
                    '[delivery_date]',
                ],
                [
                    $receiver->name,
                    $cargo->tracking_no,
                    tr_strtoupper($request->receiverNameSurnameCompany),
                    $request->receiverProximity,
                    $deliveryDate,
                ], $smsContent->content);

            SendSMS($sms, CharacterCleaner($current->gsm), 'Teslimat', 'CUMHURIYETK', $cargo->tracking_no);
        }

        if ($smstoReceiver != null) {
            $smsContent = SmsContent::where('key', 'cargo_delivery_receiver')->first();
            $sms = str_replace(
                [
                    '[name_surname]',
                    '[ctn]',
                    '[receiver_name]',
                    '[proximity]',
                    '[delivery_date]',
                ],
                [
                    $receiver->name,
                    $cargo->tracking_no,
                    tr_strtoupper($request->receiverNameSurnameCompany),
                    $request->receiverProximity,
                    $deliveryDate,
                ], $smsContent->content);

            SendSMS($sms, CharacterCleaner($receiver->gsm), 'Teslimat', 'CUMHURIYETK', $cargo->tracking_no);
        }


        return $request;
    }
}
