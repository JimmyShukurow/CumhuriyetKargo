<?php

namespace App\Actions\CKGSis\MainCargo\Delivery\AjaxTransaction;

use App\Models\Agencies;
use App\Models\CargoAddServices;
use App\Models\Cargoes;
use App\Models\CargoPartDetails;
use App\Models\Currents;
use App\Models\Delivery;
use App\Models\DeliveryDetail;
use App\Models\SmsContent;
use App\Rules\NameSurname\CurrentNameSurnameControlRule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use PHPUnit\Util\Exception;

class TransferAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = ['transferReason' => 'required'];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ['status' => '0', 'errors' => $validator->getMessageBag()->toArray()];

        $cargo = Cargoes::find($request->cargoId);

        if ($cargo->number_of_pieces > 1 && $request->selectedPieces == 'Lütfen İlgili Parçaları Seçin!') {
            return ['status' => -1, 'message' => 'Lütfen devir edilecek parçaları seçiniz!'];
        }

        if ($cargo == null)
            return ['status' => -1, 'message' => 'Kargo bulunamadı!'];

        if ($cargo->transporter != 'CK')
            return ['status' => -1, 'message' => 'Taşıyıcısı sadece Cumhuriyet Kargo olan kargolara devir girebilirsiniz!'];

        if (Auth::user()->agency_code != $cargo->arrival_agency_code)
            return ['status' => -1, 'message' => 'Kargonun varış şubesi siz olmadığınızdan bu kargoya devir giremezsiniz!'];


        if ($cargo->status == 'TESLİM EDİLDİ')
            return ['status' => -1, 'message' => 'Bu kargo teslim edildiğinden işlem yapamazsınız!'];

        $agency = Agencies::find(Auth::user()->agency_code);

        $selectedPieces = explode(',', $request->selectedPieces);

        if ($cargo->number_of_pieces != count($selectedPieces)) {

            $getNotDeliveredPieces = CargoPartDetails::where('cargo_id', $cargo->id)->where('was_delivered', 0)->get()->count();
            if ($getNotDeliveredPieces == count($selectedPieces))
                $status = "DEVİR EDİLDİ";
            else
                $status = "PARÇALI DEVİR EDİLDİ";

        } else
            $status = "DEVİR EDİLDİ";


        DB::beginTransaction();
        try {
            $createDelivery = Delivery::create([
                'cargo_id' => $cargo->id,
                'user_id' => Auth::id(),
                'agency_id' => Auth::user()->agency_code,
                'description' => tr_strtoupper($request->descriptionDelivery),
                'transfer_reason' => $request->transferReason,
                'status' => $status,
                'transaction_type' => 'DEVİR',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => -1, 'message' => 'Devir kaydı ensanısnda hata oluştu, lütfen daha sonra tekrar deneyin!'];
        }

        try {
            foreach ($selectedPieces as $key) {
                $createPieceDelivery = DeliveryDetail::create([
                    'delivery_id' => $createDelivery->id, 'cargo_id' => $cargo->id, 'part_no' => $key
                ]);
                InsertCargoMovement($cargo->tracking_no, $cargo->id, Auth::id(), $key, $agency->agency_name . ' ŞUBE kargoyu ' . $request->transferReason . ' nedeni ile devretti.', $status, rand(0, 999), 1);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => -1, 'message' => 'Parçaların devri ensanısnda hata oluştu, lütfen daha sonra tekrar deneyin!'];
        }

        try {
            $cargoUpdate = Cargoes::find($cargo->id)
                ->update(
                    [
                        'status' => $status,
                        'status_for_human' => $status,
                    ]
                );

        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => -1, 'message' => 'Devir kaydı ensanısnda hata oluştu, lütfen daha sonra tekrar deneyin!'];
        }

        $current = Currents::find($cargo->sender_id);
        $receiver = Currents::find($cargo->receiver_id);

        $smstoCurrent = CargoAddServices::where('cargo_tracking_no', $cargo->tracking_no)
            ->where('service_name', 'Göndericiye SMS')->first();

        $smstoReceiver = CargoAddServices::where('cargo_tracking_no', $cargo->tracking_no)
            ->where('service_name', 'Alıcıya SMS')->first();

        if ($smstoCurrent != null) {
            $smsContent = SmsContent::where('key', 'cargo_transfer_current')->first();
            $sms = str_replace(
                [
                    '[name_surname]',
                    '[ctn]',
                    '[transfer_reason]',
                    '[agency_name]',
                ],
                [
                    $receiver->name,
                    $cargo->tracking_no,
                    $request->transferReason,
                    $agency->agency_name,
                ], $smsContent->content);

            SendSMS($sms, CharacterCleaner($current->gsm), 'Devir', 'CUMHURIYETK', $cargo->tracking_no);
        }

        # Alıcıya her türlü sms gidiyor
        $smsContent = SmsContent::where('key', 'cargo_transfer_receiver')->first();
        $sms = str_replace(
            [
                '[name_surname]',
                '[ctn]',
                '[transfer_reason]',
                '[agency_name]',
            ],
            [
                $receiver->name,
                $cargo->tracking_no,
                $request->transferReason,
                $agency->agency_name,
            ], $smsContent->content);

        SendSMS($sms, CharacterCleaner($receiver->gsm), 'Devir', 'CUMHURIYETK', $cargo->tracking_no);

        DB::commit();
        return ['status' => 1, 'message' => 'Devir başarıyla kaydedildi!'];
    }

}
