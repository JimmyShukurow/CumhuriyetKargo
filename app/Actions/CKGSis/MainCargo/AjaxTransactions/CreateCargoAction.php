<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\AdditionalServices;
use App\Models\Agencies;
use App\Models\CargoAddServices;
use App\Models\Cargoes;
use App\Models\CargoPartDetails;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\FilePrice;
use App\Models\LocalLocation;
use App\Models\Settings;
use App\Models\SmsContent;
use App\Models\TransshipmentCenterDistricts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

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
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

        $SecondCargoType = $request->cargoType;


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


        ### Distribution Control START ###
        $distribution = DB::table('local_locations')
            ->where('city', $receiver->city)
            ->where('district', $receiver->district)
            ->where('neighborhood', $receiver->neighborhood)
            ->first();

        if ($distribution == null)
            return response()
                ->json([
                    'status' => -1,
                    'message' => 'Alıcı için dağıtım yapılmayan bölge: ' . $receiver->neighborhood
                ]);

        $arrivalAgency = DB::table('agencies')
            ->where('id', $distribution->agency_code)
            ->first();

        if ($arrivalAgency->status == '0')
            return response()
                ->json([
                    'status' => -1,
                    'message' => 'Alıcı [' . $arrivalAgency->agency_name . '] şube pasif olduğundan kargo kesimi gerçekleştiremezsiniz.'
                ]);

        $arrivalTC = getTCofAgency($arrivalAgency->id);
        ### Distribution Control END ###


        if ($request->tahsilatliKargo == 'true' && $permission_collectible_cargo->value == '0')
            return response()->json(['status' => -1, 'message' => 'Tahsilatlı kargo Türkiye geneli pasif durumda, tahsilatlı kargo çıkaramazsınız!'], 200);

        if ($request->tahsilatliKargo == 'true') {
            if ($currentType != 'Gönderici' || $currentCategory != 'Kurumsal')
                return response()->json(['status' => -1, 'message' => 'Yalnızca Kurumsal-Anlaşmalı cariler tahsilatlı kargo çıkartabilir.'], 200);
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
                $addServicePrice += $service->price;
            }


        if (doubleval($request->ekHizmetFiyat) != $addServicePrice)
            return response()
                ->json(['status' => -1, 'message' => 'Ek hizmet tutarları uyuşmuyor! Lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);


        # service price control
        $serviceFee = 0;
        if (($currentType == 'Gönderici' && $currentCategory == 'Kurumsal') || ($receiverType == 'Gönderici' && $receiverCategory == 'Kurumsal')) {
            # => contracted / En az 1 Kurumsal

            # Gönderici Kurumsal - Alıcı Bireysel
            if ($currentCategory == 'Kurumsal' && $receiverCategory == 'Bireysel') {
                # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                if ($cargoType == 'Dosya') {
                    $filePrice = $currentPrice->file_price;
                    $serviceFee = $filePrice;
                } else if ($cargoType == 'Mi') {
                    $filePrice = $currentPrice->mi_price;
                    $serviceFee = $filePrice;
                } else if ($request->gonderiTuru != 'Dosya' && $request->gonderiTuru != 'Mi') {
                    ## calc desi price
                    $desiPrice = 0;
                    if ($desi > 50) {
                        $desiPrice = $currentPrice->d_26_30;
                        $amountOfIncrease = $currentPrice->amount_of_increase;
                        for ($i = 50; $i < $desi; $i++)
                            $desiPrice += $amountOfIncrease;
                    } else
                        $desiPrice = $currentPrice[CatchDesiInterval($desi)]; #get interval                              #get interval
                    $serviceFee = $desiPrice;
                }
            }


            # Gönderici Bireysel - Alıcı Kurumsal
            if ($currentCategory == 'Bireysel' && $receiverCategory == 'Kurumsal') {
                # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();
                if ($cargoType == 'Dosya') {
                    $filePrice = $currentPrice->file_price;
                    $serviceFee = $filePrice;
                } else if ($cargoType == 'Mi') {
                    $filePrice = $currentPrice->mi_price;
                    $serviceFee = $filePrice;
                } else if ($cargoType != 'Dosya-Mi') {
                    ## calc desi price
                    $desiPrice = 0;
                    if ($desi > 30) {
                        $desiPrice = $currentPrice->d_26_30;
                        $amountOfIncrease = $currentPrice->amount_of_increase;
                        for ($i = 30; $i < $desi; $i++)
                            $desiPrice += $amountOfIncrease;
                    } else
                        $desiPrice = $currentPrice[CatchDesiInterval($desi)]; #get interval #get interval
                    $serviceFee = $desiPrice;
                }
            }

            # Gönderici Kurumsal - Alıcı Kurumsal
            if ($currentCategory == 'Kurumsal' && $receiverCategory == 'Kurumsal') {
                # ===> Ödeme Taraflı Cari Anlaşmalı Fiyat Standart Fiyat

                # ===> Gönderici Ödemeli
                if ($paymenyType == 'Gönderici Ödemeli')
                    $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                # ===> Alıcı Ödemeli
                else if ($paymenyType == 'Alıcı Ödemeli')
                    $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();


                if ($cargoType == 'Dosya-Mi') {
                    $filePrice = $currentPrice->file_price;
                    $serviceFee = $filePrice;
                } else if ($cargoType != 'Dosya-Mi') {
                    ## calc desi price
                    $desiPrice = 0;
                    if ($desi > 30) {
                        $desiPrice = $currentPrice->d_26_30;
                        $amountOfIncrease = $currentPrice->amount_of_increase;
                        for ($i = 30; $i < $desi; $i++)
                            $desiPrice += $amountOfIncrease;
                    } else
                        $desiPrice = $currentPrice[CatchDesiInterval($desi)]; #get interval                              #get interval
                    $serviceFee = $desiPrice;
                }
            }

        } else {
            # => not contracted / Bireysel - Bireysel
            if ($cargoType == 'Dosya') {

                $filePrice = FilePrice::first();
                $filePrice = $filePrice->individual_file_price;
                $serviceFee = $filePrice;

            } else if ($cargoType == 'Mi') {

                $filePrice = FilePrice::first();
                $filePrice = $filePrice->individual_mi_price;
                $serviceFee = $filePrice;

            } else if ($cargoType != 'Dosya' && $cargoType != 'Mi') {

                ## calc desi price
                $maxDesiInterval = DB::table('desi_lists')
                    ->orderBy('finish_desi', 'desc')
                    ->first();
                $maxDesiPrice = $maxDesiInterval->individual_unit_price;
                $maxDesiInterval = $maxDesiInterval->finish_desi;

                $desiPrice = 0;
                if ($desi > $maxDesiInterval) {
                    $desiPrice = $maxDesiPrice;

                    $amountOfIncrease = DB::table('settings')->where('key', 'desi_amount_of_increase')->first();
                    $amountOfIncrease = $amountOfIncrease->value;

                    for ($i = $maxDesiInterval; $i < $desi; $i++)
                        $desiPrice += $amountOfIncrease;
                } else {
                    #catch interval
                    $desiPrice = DB::table('desi_lists')
                        ->where('start_desi', '<=', $desi)
                        ->where('finish_desi', '>=', $desi)
                        ->first();
                    $desiPrice = $desiPrice->individual_unit_price;
                }
                $serviceFee = $desiPrice;
            }
        }

//                return $request->hizmetUcreti . '=>' . $serviceFee;
        if (!(compareFloatEquality($request->hizmetUcreti, $serviceFee)))
            return response()
                ->json(['status' => -1, 'message' => 'Hizmet tutarları eşleşmiyor,x lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);

        # MobileServiceFee Start
        $location = LocalLocation::where('neighborhood', $receiver->neighborhood)->first();

        $mobileServiceFee = 0;
        $filePrice = FilePrice::find(1);

        if ($location != null && $location->area_type == 'MB') {

            switch ($cargoType) {
                case 'Dosya':
                    $mobileServiceFee = $filePrice->mobile_file_price;
                    break;
                case 'Mi':
                    $mobileServiceFee = $filePrice->mobile_mi_price;
                    break;

                case 'Paket':
                case 'Koli':
                case 'Çuval':
                case 'Rulo':
                case 'Palet':
                case 'Sandık':
                case 'Valiz':
                    if ($current->mb_status == '0' || $receiver->mb_status == '0')
                        $mobileServiceFee = 0;
                    else {
                        $desi = $request->desi;

                        if ($desi > 1) {
                            ## calc desi price
                            $maxDesiInterval = DB::table('desi_lists')
                                ->orderBy('finish_desi', 'desc')
                                ->first();
                            $maxDesiPrice = $maxDesiInterval->mobile_individual_unit_price;
                            $maxDesiInterval = $maxDesiInterval->finish_desi;

                            $desiPrice = 0;
                            if ($desi > $maxDesiInterval) {
                                $desiPrice = $maxDesiPrice;

                                $amountOfIncrease = DB::table('settings')->where('key', 'mobile_desi_amount_of_increase')->first();
                                $amountOfIncrease = $amountOfIncrease->value;

                                for ($i = $maxDesiInterval; $i < $desi; $i++)
                                    $desiPrice += $amountOfIncrease;
                            } else {
                                #catch interval
                                $desiPrice = DB::table('desi_lists')
                                    ->where('start_desi', '<=', $desi)
                                    ->where('finish_desi', '>=', $desi)
                                    ->first();
                                $desiPrice = $desiPrice->mobile_individual_unit_price;
                            }
                            $mobileServiceFee = $desiPrice;
                        } else
                            $mobileServiceFee = 0;
                    }
                    break;
            }
        }
        # MobileServiceFee End

        # evrensel posta hizmetleri ücreti start
        $postServicePercent = GetSettingsVal('post_services_percent');

        $postServicePrice = ($serviceFee * $postServicePercent) / 100;
        $postServicePrice = round($postServicePrice, 2);
        # evrensel posta hizmetleri ücreti start

        if (!(compareFloatEquality($request->postaHizmetleriUcreti, $postServicePrice)))
            return response()
                ->json(['status' => -1, 'message' => 'Posta hizmetleri bedeli eşleşmiyor, lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);


        $heavyLoadCarryingStatus = false;

        $totalAgirlik = 0;
        # Control Parts Of Cargo
        if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
            $desiData = $request->desiData;
            $partQuantity = count($desiData) / 4;

            if ($partQuantity != $request->parcaSayisi)
                return response()
                    ->json(['status' => -1, 'message' => 'Hesaplanan parça sayısı (' . $request->parcaSayisi . ') ile girilen parça sayısı (' . $partQuantity . ') uyuşmuyor, Lütfen desiyi tekrar hesaplayınız!'], 200);


            $desiValues = array_values($desiData);
            $desiKeys = array_keys($desiData);

            $totalHacim = 0;
            $totalDesi = 0;
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

                //echo $en . ' ' . $boy . ' ' . $yukseklik . ' ' . $agirlik;
                # calc hacim
                $hacim = ($en * $boy * $yukseklik) / 1000000;
                $hacim = round($hacim, 5);
                $totalHacim += $hacim;

                #calc desi
                $desi = ($en * $boy * $yukseklik) / 3000;
                $desi = $agirlik > $desi ? $agirlik : $desi;
                $totalDesi += round($desi, 2);

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

                $totalAgirlik += $agirlik;

                if ($desi > 100 || $agirlik > 300)
                    $heavyLoadCarryingStatus = true;

                if (count($desiKeys) == 0)
                    break;
            }

            // return $totalDesi . ' => ' . $request->desi;
            if (!compareFloatEquality($request->desi, $totalDesi))
                return response()
                    ->json(['status' => -1, 'message' => 'Hesaplanan desi ile girilen desi eşleşmiyor, lütfen desiyi tekrar hesaplayınız!'], 200);

            if (!compareFloatEquality($request->totalHacim, $totalHacim))
                return response()
                    ->json(['status' => -1, 'message' => 'Toplam hacim eşleşmiyor, lütfen desiyi tekrar hesaplayınız!'], 200);

            # return $totalHacim . ' => ' . $totalDesi;
        }

        if (($cargoType != 'Dosya' && $cargoType != 'Mi') && $heavyLoadCarryingStatus == true && $request->parcaSayisi == 1) {
            $heavyLoadCarryingCost = GetSettingsVal('heavy_load_carrying_cost');
            $heavyLoadCarryingCost = $heavyLoadCarryingCost + (($heavyLoadCarryingCost * 18) / 100);
        } else
            $heavyLoadCarryingCost = 0;

//                return $request->agirYukTasimaBedeli . ' - ' . $heavyLoadCarryingCost;
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
            $cubic_meter_volume = $totalHacim;
            $desi = $totalDesi;
        } else {
            $number_of_pieces = 1;
            $cubic_meter_volume = 1;
            $desi = 0;
        }

        $invoiceNumber = DesignInvoiceNumber();

        $transporter = 'YK';

        if ($transporter == 'YK') {
            $arrivalAgency->id = -1;
            $arrivalTC->id = -1;
        }

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
            'kg' => $totalAgirlik,
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
//            'transporter' => 'CK',
            'transporter' => $transporter,
            'system' => 'CKG-Sis',
        ]);

        # Get Movement Text
        $info = DB::table('cargo_movement_contents')
            ->where('key', 'agency_create_cargo')
            ->first();

        $agency = DB::table('agencies')
            ->where('id', Auth::user()->agency_code)
            ->first();
        $infoText = str_replace(['[agency]'], [$agency->city . ' - ' . $agency->agency_name], $info->content);


        if ($CreateCargo) {

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


            $group_id = uniqid('n_');
            ## INSERT Cargo Parts START
            if ($cargoType != 'Dosya' && $cargoType != 'Mi') {

                $desiValues = array_values($desiData);
                $desiKeys = array_keys($desiData);

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
                #inert debit
                $insert = InsertDebits($ctn, $CreateCargo->id, 1, Auth::id(), $insert->id);
                # INSERT Movements END
            }
            ## INSERT Cargo Parts END


//                    return CharacterCleaner($receiver->gsm);
//                    return CharacterCleaner($current->gsm);

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

                return response()
                    ->json(['status' => 1, 'message' => 'İşlem başarılı, Kargo oluşturuldu!'], 200);
            } else
                return response()
                    ->json(['status' => -1, 'message' => 'Bir hata oluştu, sistem destek ile iletişime geçin!'], 200);

        }
        # end create new Cargo


        return $request->all();
    }
}
