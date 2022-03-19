<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\AdditionalServices;
use App\Models\Agencies;
use App\Models\CargoAddServices;
use App\Models\CargoCollection;
use App\Models\Cargoes;
use App\Models\CargoPartDetails;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\FilePrice;
use App\Models\LocalLocation;
use App\Models\Settings;
use App\Models\SmsContent;
use App\Models\TransshipmentCenterDistricts;
use App\Models\TransshipmentCenters;
use FontLib\TrueType\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Exception;

class CreateCargoAction
{
    use AsAction;

    public function handle($request)
    {

        # START Control Permission Of Create Cargo START
        $agency = Agencies::find(Auth::user()->agency_code);
        if ($agency->permission_of_create_cargo == '0')
            return response()
                ->json(['status' => -1, 'message' => 'Kargo kesiminize izin yok!'], 200);

        if ($agency->safe_status == '0')
            return response()
                ->json(['status' => -1, 'message' => 'Kasanız Kapalı! Açıklama: [' . $agency->safe_status_description . ']'], 200);

        # END Control Permission Of Create Cargo END

        $rules = [
            'gondericiCariKodu' => 'required',
            'aliciCariKodu' => 'required',
            'gonderiTuru' => 'required',
            'odemeTipi' => 'required',
            'desi' => 'required',
            'parcaSayisi' => 'required',
            'tahsilatliKargo' => 'required',
            'mesafe' => 'required',
            'ekHizmetFiyat' => 'required',
            'hizmetUcreti' => 'required',
            'postaHizmetleriUcreti' => 'required',
            'agirYukTasimaBedeli' => 'required',
            'genelToplam' => 'required',
            'totalHacim' => 'required',
            'collectionDetails' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

        ## YK-BAD
        if ($request->odemeTipi == 'Alıcı Ödemeli')
            return response()
                ->json(['status' => -1, 'message' => 'Alıcı ödemeli kargo kesimine izin verilmiyor!'], 200);

        if (($request->gonderiTuru != 'Dosya' && $request->gonderiTuru != 'Mi') && $request->desi == 0)
            return response()
                ->json(['status' => -1, 'message' => 'Lütfen ' . $request->gonderiTuru . ' için desi bilgisi giriniz!'], 200);

        if ($request->odemeTipi == 'Alıcı Ödemeli' && $request->tahsilatliKargo == 'true')
            return response()
                ->json(['status' => -1, 'message' => 'Alıcı ödemeli tahsilatlı kargo çıkaramazsınız, Sadece gönderici ödemeli tahsilatlı kargo çıkarılabilir!'], 200);


        if (!collect(['NAKİT'])->contains($request->collectionDetails['collectionType']))
            return response()
                ->json(['status' => -1, 'message' => 'Lütfen geçerli bir tahsilat ödeme tipi girin!'], 200);


        $currentCode = str_replace(' ', '', $request->gondericiCariKodu);
        $receiverCode = str_replace(' ', '', $request->aliciCariKodu);

        $current = Currents::where('current_code', $currentCode)->first();
        $receiver = Currents::where('current_code', $receiverCode)->first();

        if ($current == null)
            return response()->json(['status' => -1, 'message' => 'Gönderici bulunamadı!'], 200);
        if ($receiver == null)
            return response()->json(['status' => -1, 'message' => 'Alıcı bulunamadı!'], 200);

        $currentType = $current->current_type;
        $currentCategory = $current->category;

        $receiverType = $receiver->current_type;
        $receiverCategory = $receiver->category;
        $mesafe = str_replace(',', '', $request->mesafe);

        $desi = $request->desi;
        $cargoType = $request->gonderiTuru;
        $paymenyType = $request->odemeTipi;
        $permission_collectible_cargo = Settings::where('key', 'collectible_cargo')->first();


        $distribution = DistributionControlAction::run($request);
        $arrivalTC = TransshipmentCenters::find(9);
        $arrivalAgency = Agencies::find(1);

        $transporter = null;
        if ($distribution['status'] == '1' && $distribution['area_type'] == 'MNG') {
            $transporter = 'MNG';
            $arrivalAgency->id = -1;
            $arrivalTC->id = -1;
        } else if ($distribution['status'] == '1' && $distribution['area_type'] != 'MNG') {
            $transporter = 'CK';
            $arrivalTC = TransshipmentCenters::find($distribution['arrival_tc_code']);
            $arrivalAgency = Agencies::find($distribution['arrival_agency_code']);
        }


        ## customers control
        $currentState = CurrentControl($current->current_code);
        $receiverState = CurrentControl($receiver->current_code);

        if ($currentState['status'] != 1)
            return response()
                ->json(['status' => -1, 'message' => 'Gönderici hatalı! ' . $currentState['result']], 200);

        if ($receiverState['status'] != 1)
            return response()
                ->json(['status' => -1, 'message' => 'Alıcı hatalı!' . $receiverState['result']], 200);


        # control distance and price
        $distance = calcDistance($current->city, $receiver->city);
        $distancePrice = calcDistancePrice($distance);

        if ($mesafe != $distance || $request->mesafeUcreti != $distancePrice)
            return response()
                ->json(['status' => -1, 'message' => 'Mesafeler eşleşmiyor, lütfen göndericiyi ve alıcıyı güncelleyiniz'], 200);


        # control additional services
        $addServicePrice = 0;
        $addServices = $request->addServicesData;
        if (is_array($addServices))
            foreach ($addServices as $key => $value) {
                $serviceID = substr($key, 12, strlen($key));
                $service = AdditionalServices::find($serviceID);
                if ($service == null) {
                    return response()
                        ->json(['status' => -1, 'message' => 'Bulunamayan ek hizmet! Lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);
                    break;
                } else if ($service->status == '0') {
                    return response()
                        ->json(['status' => -1, 'message' => $service->service_name . ' hizmeti şu anda pasif durumda, bu hizmeti şu anda kulanamazsınız!'], 200);
                    break;
                }
                if ($transporter == 'MNG' && $service->partner_status == '0') {
                    return response()
                        ->json(['status' => -1, 'message' => 'Taşıyan partner firma olduğundan ' . $service->service_name . ' hizmetini kulanamazsınız!'], 200);
                }
                $addServicePrice += $service->price;
            }


        if (doubleval($request->ekHizmetFiyat) != $addServicePrice)
            return response()
                ->json(['status' => -1, 'message' => 'Ek hizmet tutarları uyuşmuyor! Lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);


        $dataGeneralServiceFee = GetPriceForCustomersAction::run($request);
        $serviceFee = $dataGeneralServiceFee['service_fee'];
        $heavyLoadCarryingCost = $dataGeneralServiceFee['heavy_load_carrying_cost'];
        $mobileServiceFee = $dataGeneralServiceFee['mobile_service_fee'];
        $postServicePrice = $dataGeneralServiceFee['post_service_price'];
        $totalWeight = $dataGeneralServiceFee['total_weight'];
        $partQuantity = $dataGeneralServiceFee['part_quantity'];
        $totalDesi = $dataGeneralServiceFee['total_desi'];
        $totalVolume = $dataGeneralServiceFee['total_volume'];

        if ($partQuantity != $request->parcaSayisi)
            return response()
                ->json(['status' => -1, 'message' => 'Hesaplanan parça sayısı (' . $request->parcaSayisi . ') ile girilen parça sayısı (' . $partQuantity . ') uyuşmuyor, Lütfen desiyi tekrar hesaplayınız!'], 200);


        if (!(compareFloatEquality($request->hizmetUcreti, $serviceFee)))
            return response()
                ->json(['status' => -1, 'message' => 'Hizmet tutarları eşleşmiyor, lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);


        //return $request->agirYukTasimaBedeli . ' - ' . $heavyLoadCarryingCost;
        if (!(compareFloatEquality($request->agirYukTasimaBedeli, $heavyLoadCarryingCost)))
            return response()
                ->json(['status' => -1, 'message' => 'Ağır yük taşıma bedeli eşleşmiyor, lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);


        $currentAddress = AddressMaker($current->city, $current->district, $current->neighborhood, $current->street, $current->street2, $current->building_no, $current->floor, $current->door_no, $current->address_note);
        $receiverAddress = AddressMaker($receiver->city, $receiver->district, $receiver->neighborhood, $receiver->street, $receiver->street2, $receiver->building_no, $receiver->floor, $receiver->door_no, $receiver->address_note);
        $departureAgency = Agencies::find(Auth::user()->agency_code);

        $tc = TransshipmentCenterDistricts::where('city', $departureAgency->city)
            ->where('district', $departureAgency->district)
            ->first();

        ## calc total price
        $totalPriceExceptKdv = $distancePrice + $addServicePrice + $serviceFee + $mobileServiceFee + $postServicePrice;
        $kdvPrice = $totalPriceExceptKdv * 0.18;
        $kdvPrice = round($kdvPrice, 2);
        $totalPrice = $totalPriceExceptKdv + $kdvPrice + $heavyLoadCarryingCost;

//                return $totalPrice . ' ' . $request->genelToplam;
        if ("$totalPrice" != "$request->genelToplam")
            return response()
                ->json(['status' => -1, 'message' => 'Genel toplamlar eşleşmiyor, lütfen sistem destek ile iletişime geçin!'], 200);

        $collection = collect(array_keys($addServices));
        $homeDelivery = $collection->contains('add-service-8') ? '1' : '0';

        $collection = collect(array_keys($addServices));
        $pickUpAddress = $collection->contains('add-service-21') ? '1' : '0';

        $ctn = CreateCargoTrackingNo(Auth::user()->agency_code);

        $userGeneralInfo = DB::table('view_users_all_info')
            ->where('id', Auth::id())
            ->first();

        if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
            $number_of_pieces = $partQuantity;
            $cubic_meter_volume = $totalVolume;
            $desi = $totalDesi;
        } else {
            $number_of_pieces = 1;
            $cubic_meter_volume = 1;
            $desi = 0;
        }

        $invoiceNumber = DesignInvoiceNumber();


        DB::beginTransaction();
        try {
            # start create new Cargo
            $CreateCargo = Cargoes::create([
                'receiver_id' => $receiver->id,
                'receiver_name' => $receiver->name,
                'receiver_phone' => $receiver->gsm,
                'receiver_city' => $receiver->city,
                'receiver_district' => $receiver->district,
                'receiver_neighborhood' => $receiver->neighborhood,
                'receiver_street' => $receiver->street,
                'receiver_street2' => $receiver->street2,
                'receiver_building_no' => $receiver->building_no,
                'receiver_door_no' => $receiver->door_no,
                'receiver_floor' => $receiver->floor,
                'receiver_address_note' => $receiver->address_note,
                'receiver_address' => $receiverAddress,
                'sender_id' => $current->id,
                'sender_name' => $current->name,
                'sender_phone' => $current->gsm,
                'sender_city' => $current->city,
                'sender_district' => $current->district,
                'sender_neighborhood' => $current->neighborhood,
                'sender_street' => $current->street,
                'sender_street2' => $current->street2,
                'sender_building_no' => $current->building_no,
                'sender_door_no' => $current->door_no,
                'sender_floor' => $current->floor,
                'sender_address_note' => $current->address_note,
                'sender_address' => $currentAddress,
                'customer_code' => $request->musteriKodu,
                'payment_type' => $request->odemeTipi,
                'number_of_pieces' => $number_of_pieces,
                'cargo_type' => $cargoType,
                'cargo_content' => tr_strtoupper($request->kargoIcerigi),
                'cargo_content_ex' => tr_strtoupper($request->kargoIcerigiAciklama),
                'tracking_no' => $ctn,
                'invoice_number' => $invoiceNumber,
                'arrival_city' => $receiver->city,
                'arrival_district' => $receiver->district,
                'arrival_agency_code' => $arrivalAgency->id,
                'arrival_tc_code' => $arrivalTC->id,
                'departure_city' => $userGeneralInfo->branch_city,
                'departure_district' => $userGeneralInfo->branch_district,
                'departure_agency_code' => Auth::user()->agency_code,
                'departure_tc_code' => $tc->tc_id,
                'creator_agency_code' => Auth::user()->agency_code,
                'creator_user_id' => Auth::id(),
                'status' => 'İRSALİYE KESİLDİ',
                'collectible' => $request->tahsilatliKargo == 'true' ? '1' : '0',
                'collection_fee' => $request->tahsilatliKargo == 'true' ? getDoubleValue($request->faturaTutari) : 0,
                'collection_payment_type' => $request->tahsilatliKargo == 'true' ? 'Nakit' : '0',
                'desi' => $desi,
                'kg' => $totalWeight,
                'kdv_percent' => 18,
                'cubic_meter_volume' => $cubic_meter_volume,
                'kdv_price' => $kdvPrice,
                'distance' => $distance,
                'distance_price' => $distancePrice,
                'service_price' => $serviceFee,
                'mobile_service_price' => $mobileServiceFee,
                'add_service_price' => $addServicePrice,
                'post_service_price' => $postServicePrice,
                'heavy_load_carrying_cost' => $heavyLoadCarryingCost,
                'total_price' => $totalPrice,
                'home_delivery' => $homeDelivery,
                'pick_up_address' => $pickUpAddress,
                'agency_delivery' => $homeDelivery == '1' ? '0' : '1',
                'status_for_human' => 'HAZIRLANIYOR',
                'transporter' => $transporter,
                'system' => 'CKG-Sis',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => -1, 'message' => 'Kargo kaydı esnasında hata oluştu  ' . $e->getMessage() . ' line :' . $e->getLine()]);
        }

        # Get Movement Text
        $info = DB::table('cargo_movement_contents')
            ->where('key', 'agency_create_cargo')
            ->first();

        $agency = DB::table('agencies')
            ->where('id', Auth::user()->agency_code)
            ->first();
        $infoText = str_replace(['[agency]'], [$agency->city . ' - ' . $agency->agency_name], $info->content);


        if ($CreateCargo) {
            try {

                ## Insert Add Services START
                foreach ($addServices as $key => $value) {
                    $serviceID = substr($key, 12, strlen($key));
                    $service = AdditionalServices::find($serviceID);
                    $insert = CargoAddServices::create([
                        'cargo_tracking_no' => $ctn,
                        'add_service_id' => $serviceID,
                        'service_name' => $service->service_name,
                        'price' => $service->price
                    ]);

                    if (!$insert) {
                        activity()
                            ->performedOn($CreateCargo)
                            ->inLog('Critical Error')
                            ->withProperties(['ktno' => $ctn, 'user' => Auth::id(), 'service-id' => $serviceID])
                            ->log('Ek servis eklenirken hata oluştu!');

                        return response()
                            ->json(['status' => -1, 'message' => 'Bir hata oluştu, sistem destek ile iletişime geçin! [CargoAddService]'], 200);
                        break;
                    }
                }
                # Insert Add Services END
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['status' => -1, 'message' => 'Ek hizmetlerin kaydı esnasında hata oluştu']);
            }

            try {

                if ($request->odemeTipi == 'Gönderici Ödemeli') {
                    $collectionPaymentType = $request->collectionDetails['collectionType'];
                    $collectionEntered = 'EVET';
                    $enteredUserId = Auth::id();
                } else {
                    $collectionPaymentType = null;
                    $collectionEntered = 'HAYIR';
                    $enteredUserId = null;
                }

                $createCargoCollectionDetails = CargoCollection::create([
                    'cargo_id' => $CreateCargo->id,
                    'collection_entered' => $collectionEntered,
                    'collection_entered_user_id' => $enteredUserId,
                    'collection_type_entered' => $collectionPaymentType,
                    'confirm_code' => $request->collectionDetails['collectionConfirmationCode'],
                    'card_owner_name' => $request->collectionDetails['collectionCardOwner'],
                    'description' => $request->collectionDetails['collectionDescription']
                ]);

            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['status' => -1, 'exception' => $e->getMessage(), 'message' => 'Tahsilat detay kaydı esnasında hata oluştu']);
            }


            $group_id = uniqid('n_');
            ## INSERT Cargo Parts START
            if ($cargoType != 'Dosya' && $cargoType != 'Mi') {

                $desiValues = array_values($request->desiData);
                $desiKeys = array_keys($request->desiData);

                $totalHacim = 0;
                $totalDesi = 0;
                $reversePartQuantity = $partQuantity;
                while (true) {

                    $i = 0;
                    $en = 0;
                    $boy = 0;
                    $yukseklik = 0;
                    $agirlik = 0;
                    $hacim = 1;
                    $desi = 1;

                    if (str_contains($desiKeys[$i], 'En'))
                        $en = $desiValues[$i];

                    if (str_contains($desiKeys[$i + 1], 'Boy'))
                        $boy = $desiValues[$i + 1];

                    if (str_contains($desiKeys[$i + 2], 'Yukseklik'))
                        $yukseklik = $desiValues[$i + 2];

                    if (str_contains($desiKeys[$i + 3], 'Agirlik'))
                        $agirlik = $desiValues[$i + 3];

                    // echo $en . ' ' . $boy . ' ' . $yukseklik . ' ' . $agirlik;
                    # calc hacim
                    $hacim = ($en * $boy * $yukseklik) / 1000000;
                    $hacim = round($hacim, 5);

                    #calc desi
                    $desi = ($en * $boy * $yukseklik) / 3000;
                    $desi = $agirlik > $desi ? $agirlik : $desi;
                    $desi = round($desi, 2);
                    try {
                        $insert = CargoPartDetails::create([
                            'cargo_id' => $CreateCargo->id,
                            'tracking_no' => $ctn,
                            'part_no' => $reversePartQuantity,
                            'width' => $en,
                            'size' => $boy,
                            'height' => $yukseklik,
                            'weight' => $agirlik,
                            'desi' => $desi,
                            'cubic_meter_volume' => $hacim
                        ]);

                        # INSERT Movements START
                        $insert = InsertCargoMovement($ctn, $CreateCargo->id, Auth::id(), $reversePartQuantity, $infoText, $info->status, $group_id);
                        #inert debit
                        $insert = InsertDebits($ctn, $CreateCargo->id, $reversePartQuantity, Auth::id(), $insert->id);
                        # INSERT Movements END

                    } catch (Exception $e) {
                        DB::rollBack();
//                        return response()->json(['status' => -1,  'message' => 'Kargo parça ekleme esnasında hata oluştu']);
                        return response()->json(['status' => -1, 'message' => 'Kargo parça ekleme esnasında hata oluştu']);
                    }

                    if ($insert)
                        $reversePartQuantity--;
                    else {
                        activity()
                            ->performedOn($CreateCargo)
                            ->inLog('Critical Error')
                            ->withProperties(['ktno' => $ctn, 'user' => Auth::id(), 'part_no' => $reversePartQuantity])
                            ->log('Parçalı kargo eklenirken hata oluştu!');

                        return response()
                            ->json(['status' => -1, 'message' => 'Bir hata oluştu, sistem destek ile iletişime geçin! [CargoParts]'], 200);
                        break;
                    }

                    unset($desiKeys[$i]);
                    unset($desiValues[$i]);

                    unset($desiKeys[$i + 1]);
                    unset($desiValues[$i + 1]);

                    unset($desiKeys[$i + 2]);
                    unset($desiValues[$i + 2]);

                    unset($desiKeys[$i + 3]);
                    unset($desiValues[$i + 3]);
                    #re-indexing
                    $desiValues = array_values($desiValues);
                    $desiKeys = array_values($desiKeys);

                    if (count($desiKeys) == 0)
                        break;
                }
            } else {

                try {
                    $insert = CargoPartDetails::create([
                        'cargo_id' => $CreateCargo->id,
                        'tracking_no' => $ctn,
                        'part_no' => 1,
                        'width' => 0,
                        'size' => 0,
                        'height' => 0,
                        'weight' => 0,
                        'desi' => 0,
                        'cubic_meter_volume' => 0
                    ]);

                    # INSERT Movements START
                    $insert = InsertCargoMovement($ctn, $CreateCargo->id, Auth::id(), 1, $infoText, $info->status, $group_id);
                    #insert debit
                    $insert = InsertDebits($ctn, $CreateCargo->id, 1, Auth::id(), $insert->id);
                    # INSERT Movements END
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => -1, 'message' => 'Parçaların kaydı esnasında hata oluştu!']);
                }

            }
            ## INSERT Cargo Parts END

            ## SMS Transactions
            if ($insert != false) {

                $smstoCurrent = CargoAddServices::where('cargo_tracking_no', $ctn)
                    ->where('service_name', 'Göndericiye SMS')
                    ->first();
                $smstoCurrent = $smstoCurrent == null ? false : true;

                $smstoReceiver = CargoAddServices::where('cargo_tracking_no', $ctn)
                    ->where('service_name', 'Alıcıya SMS')
                    ->first();
                $smstoReceiver = $smstoReceiver == null ? false : true;

                if ($smstoCurrent == true) {
                    $smsContent = SmsContent::where('key', 'new_cargo_current')->first();
                    $sms = str_replace(['[name_surname]', '[tracking_no]'], [$current->name, $ctn], $smsContent->content);
                    SendSMS($sms, CharacterCleaner($current->gsm), 'Yeni Kargo', 'CUMHURIYETK', $ctn);
                }

                if ($smstoReceiver == true) {
                    $smsContent = SmsContent::where('key', 'new_cargo_receiver')->first();
                    $sms = str_replace(['[name_surname]', '[tracking_no]'], [$receiver->name, $ctn], $smsContent->content);
                    SendSMS($sms, CharacterCleaner($receiver->gsm), 'Yeni Kargo', 'CUMHURIYETK', $ctn);
                }

                DB::commit();
                return response()
                    ->json(['status' => 1, 'message' => 'İşlem başarılı, Kargo oluşturuldu!'], 200);
            } else {
                DB::rollBack();
                return response()
                    ->json(['status' => -1, 'message' => 'Bir hata oluştu, sistem destek ile iletişime geçin!'], 200);
            }
        }
        # end create new Cargo

        return $request->all();
    }
}
