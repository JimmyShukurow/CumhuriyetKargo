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
use App\Rules\CurrentNameControlRule;
use App\Rules\NameSurname\CurrentNameSurnameControlRule;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use PHPUnit\Util\Exception;

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
            'selectedPieces' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return ['status' => '0', 'errors' => $validator->getMessageBag()->toArray()];

        $deliveryDate = Carbon::parse($request->deliveryDate)->format('d/m/Y H:m');
        $deliveryDateSql = Carbon::parse($request->deliveryDate)->format('Y-m-d H:m:s');


        $cargo = Cargoes::find($request->cargoId);

        if ($cargo->number_of_pieces > 1 && $request->selectedPieces == 'Lütfen İlgili Parçaları Seçin!') {
            return ['status' => -1, 'message' => 'Lütfen teslim edilecek parçaları seçiniz!'];
        }

        if ($cargo == null)
            return ['status' => -1, 'message' => 'Kargo bulunamadı!'];

        if ($cargo->transporter != 'CK')
            return ['status' => -1, 'message' => 'Taşıyıcısı sadece Cumhuriyet Kargo olan kargolara teslimat girebilirsiniz!'];

        if (Auth::user()->agency_code != $cargo->arrival_agency_code)
            return ['status' => -1, 'message' => 'Kargonun varış şubesi siz olmadığınızdan bu kargoya teslimat giremezsiniz!'];


        if ($cargo->status == 'TESLİM EDİLDİ')
            return ['status' => -1, 'message' => 'Bu kargo teslim edildiğinden işlem yapamazsınız!'];

        $selectedPieces = explode(',', $request->selectedPieces);

        if ($cargo->number_of_pieces != count($selectedPieces)) {

            $getNotDeliveredPieces = CargoPartDetails::where('cargo_id', $cargo->id)->where('was_delivered', 0)->get()->count();
            if ($getNotDeliveredPieces == count($selectedPieces))
                $status = "TESLİM EDİLDİ";
            else
                $status = "PARÇALI TESLİM EDİLDİ";

        } else
            $status = "TESLİM EDİLDİ";


        DB::beginTransaction();
        try {
            $createDelivery = Delivery::create([
                'cargo_id' => $cargo->id,
                'user_id' => Auth::id(),
                'agency_id' => Auth::user()->agency_code,
                'description' => tr_strtoupper($request->descriptionDelivery),
                'receiver_name_surname' => tr_strtoupper($request->teslimAlanAdSoyad),
                'receiver_tckn_vkn' => $request->receiverTCKN,
                'degree_of_proximity' => $request->receiverProximity,
                'delivery_date' => $deliveryDateSql,
                'status' => $status,
                'transaction_type' => 'TESLİMAT',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => -1, 'message' => 'Teslimat kaydı ensanısnda hata oluştu, lütfen daha sonra tekrar deneyin!'];
        }

        try {
            foreach ($selectedPieces as $key) {

                $cargoPart = CargoPartDetails::where('cargo_id', $cargo->id)
                    ->where('part_no', $key)->first();

                if ($cargoPart == null)
                    throw new Exception('Parça bulunamadı!');

                $cargoPart->was_delivered = 1;
                $cargoPart->save();

                $createPieceDelivery = DeliveryDetail::create([
                    'delivery_id' => $createDelivery->id, 'cargo_id' => $cargo->id, 'part_no' => $key
                ]);

                # Get Movement Text
                $info = DB::table('cargo_movement_contents')
                    ->where('key', 'delivery_cargo')
                    ->first();
                $branch = getUserBranchInfo();

                $infoText = str_replace(
                    [
                        '[branch]',
                        '[receiver]',
                        '[proximity]',
                    ],
                    [
                        $branch['name'] . ' ' . $branch['type'],
                        tr_strtoupper($request->teslimAlanAdSoyad),
                        $request->receiverProximity,
                    ], $info->content);

                InsertCargoMovement($cargo->tracking_no, $cargo->id, Auth::id(), $key, $infoText, $status, rand(0, 999), 1);

            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => -1, 'message' => 'Parçaların teslimat kaydı ensanısnda hata oluştu, lütfen daha sonra tekrar deneyin!', 'e' => $e->getMessage()];
        }

        try {
            $cargoUpdate = Cargoes::find($cargo->id)
                ->update(
                    [
                        'status' => $status,
                        'status_for_human' => $status,
                        'delivery_date' => $deliveryDateSql,
                        'cargo_receiver_name' => tr_strtoupper($request->teslimAlanAdSoyad),
                    ]
                );

        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => -1, 'message' => 'Teslimat kaydı ensanısnda hata oluştu, lütfen daha sonra tekrar deneyin!'];
        }


        $current = Currents::find($cargo->sender_id);
        $receiver = Currents::find($cargo->receiver_id);

        $smstoCurrent = CargoAddServices::where('cargo_tracking_no', $cargo->tracking_no)
            ->where('service_name', 'Göndericiye SMS')->first();

        $smstoReceiver = CargoAddServices::where('cargo_tracking_no', $cargo->tracking_no)
            ->where('service_name', 'Alıcıya SMS')->first();

        $hourDiff = Carbon::parse($deliveryDateSql)->diffInHours(Carbon::now());

        if ($hourDiff < 24) {

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
                        tr_strtoupper($request->teslimAlanAdSoyad),
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
                        tr_strtoupper($request->teslimAlanAdSoyad),
                        $request->receiverProximity,
                        $deliveryDate,
                    ], $smsContent->content);

                SendSMS($sms, CharacterCleaner($receiver->gsm), 'Teslimat', 'CUMHURIYETK', $cargo->tracking_no);
            }
        }

        DB::commit();

        return ['status' => 1, 'message' => 'Teslimat başarıyla kaydedildi!'];
    }
}
