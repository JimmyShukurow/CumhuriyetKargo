<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\FilePrice;
use App\Models\LocalLocation;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPriceForCustomersAction
{
    use AsAction;

    public function handle($request)
    {
        $desi = $request->desi;
        $cargoType = $request->cargoType;
        $currentCode = str_replace(' ', '', $request->gondericiCariKodu);
        $receiverCode = str_replace(' ', '', $request->aliciCariKodu);
        $paymenyType = $request->odemeTipi;

        $current = Currents::where('current_code', $currentCode)->first();
        $receiver = Currents::where('current_code', $receiverCode)->first();

        $currentType = $current->current_type;
        $currentCategory = $current->category;

        $receiverType = $receiver->current_type;
        $receiverCategory = $receiver->category;

        $distribution = DistributionControlAction::run($request);

        $partQuantity = 1;
        $totalDesi = 0;
        $totalHacim = 0;

        if (($cargoType != 'Dosya' && $cargoType != 'Mi') && $desi == 0) {
            $desiPrice = 0;
            $json = ['service_fee' => $desiPrice];
        } else {

            if ($distribution['area_type'] == 'MNG') {
                # => not contracted / Bireysel - Bireysel
                if ($cargoType == 'Dosya') {
                    $filePrice = FilePrice::first();
                    $filePrice = $filePrice->corporate_file_price;
                    $json = ['service_fee' => $filePrice];
                } else if ($cargoType == 'Mi') {
                    $filePrice = FilePrice::first();
                    $filePrice = $filePrice->corporate_mi_price;
                    $json = ['service_fee' => $filePrice];
                } else {
                    #parça başı start
                    $parcaBasiFiyat = 0;
                    $totalAgirlik = 0;
                    # Control Parts Of Cargo
                    if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
                        $desiData = $request->desiData;
                        $partQuantity = count($desiData) / 4;

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


                            $parcaBasiFiyat = $parcaBasiFiyat + getPartnerDesiPrice($desi);
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
                        $desiPrice = $parcaBasiFiyat;
                    }

                    $json = ['service_fee' => $desiPrice];
                }
            } else {

                if ($currentCategory == 'Anlaşmalı' || $receiverCategory == 'Anlaşmalı') {
                    # => contracted / En az 1 Anlaşmalı

                    # Gönderici Anlaşmalı - Alıcı Anlaşmalı
                    if ($currentCategory == 'Anlaşmalı' && $receiverCategory == 'Anlaşmalı') {
                        # ===> Ödeme Taraflı Cari Anlaşmalı Fiyat Standart Fiyat

                        # ===> Gönderici Ödemeli
                        if ($paymenyType == 'Gönderici Ödemeli')
                            $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                        # ===> Alıcı Ödemeli
                        else if ($paymenyType == 'Alıcı Ödemeli')
                            $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();

                        if ($cargoType == 'Dosya') {
                            $filePrice = $currentPrice->file;
                            $json = ['service_fee' => $filePrice];
                        } else if ($cargoType == 'Mi') {
                            $filePrice = $currentPrice->mi;
                            $json = ['service_fee' => $filePrice];
                        } else if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
                            ## calc desi price
                            $desiPrice = 0;
                            if ($desi > 30) {
                                $desiPrice = $currentPrice->d_26_30;
                                $amountOfIncrease = $currentPrice->amount_of_increase;
                                for ($i = 30; $i < $desi; $i++)
                                    $desiPrice += $amountOfIncrease;
                            } else
                                $desiPrice = $currentPrice[CatchDesiInterval($desi)]; #get interval                              #get interval
                            $json = ['service_fee' => $desiPrice];
                        }
                    }

                    # Gönderici Anlaşmalı ise
                    if ($currentCategory == 'Anlaşmalı') {
                        # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                        $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                        if ($cargoType == 'Dosya') {
                            $filePrice = $currentPrice->file;
                            $json = ['service_fee' => $filePrice];
                        } else if ($cargoType == 'Mi') {
                            $filePrice = $currentPrice->mi;
                            $json = ['service_fee' => $filePrice];
                        } else if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
                            ## calc desi price
                            $desiPrice = 0;
                            if ($desi > 30) {
                                $desiPrice = $currentPrice->d_26_30;
                                $amountOfIncrease = $currentPrice->amount_of_increase;
                                for ($i = 30; $i < $desi; $i++)
                                    $desiPrice += $amountOfIncrease;
                            } else
                                $desiPrice = $currentPrice[CatchDesiInterval($desi)]; #get interval                              #get interval
                            $json = ['service_fee' => $desiPrice];
                        }
                    }

                    # Alıcı Anlaşmalı ise
                    if ($receiverCategory == 'Anlaşmalı') {
                        # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                        $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();
                        if ($cargoType == 'Dosya') {
                            $filePrice = $currentPrice->file;
                            $json = ['service_fee' => $filePrice];
                        } else if ($cargoType == 'Mi') {
                            $filePrice = $currentPrice->mi;
                            $json = ['service_fee' => $filePrice];
                        } else if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
                            ## calc desi price
                            $desiPrice = 0;
                            if ($desi > 30) {
                                $desiPrice = $currentPrice->d_26_30;
                                $amountOfIncrease = $currentPrice->amount_of_increase;
                                for ($i = 30; $i < $desi; $i++)
                                    $desiPrice += $amountOfIncrease;
                            } else
                                $desiPrice = $currentPrice[CatchDesiInterval($desi)]; #get interval                              #get interval
                            $json = ['service_fee' => $desiPrice];
                        }
                    }

                } else {
                    # => not contracted / Bireysel - Bireysel
                    if ($cargoType == 'Dosya') {
                        $filePrice = FilePrice::first();
                        $filePrice = $filePrice->individual_file_price;
                        $json = ['service_fee' => $filePrice];
                    } else if ($cargoType == 'Mi') {
                        $filePrice = FilePrice::first();
                        $filePrice = $filePrice->individual_mi_price;
                        $json = ['service_fee' => $filePrice];
                    } else {
                        $desiPrice = getDesiPrice($desi);
                        $json = ['service_fee' => $desiPrice];
                    }
                }
            }

        }

        # MobileServiceFee Start
        $location = LocalLocation::where('neighborhood', $receiver->neighborhood)->first();

        $mobileServiceFee = 0;
        $filePrice = FilePrice::find(1);

        ## YK-BAD
        if ($distribution['area_type'] == 'MNG' || $receiver->mb_status == '0')
            $mobileServiceFee = 0;
        else
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

        $postServicePrice = ($json['service_fee'] * $postServicePercent) / 100;
        $postServicePrice = round($postServicePrice, 2);
        # evrensel posta hizmetleri ücreti end

        ##  New Heavy Load Carrying Coast Calc START
        $heavyLoadCarryingStatus = false;
        $totalAgirlik = 0;
        # Control Parts Of Cargo
        if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
            $desiData = $request->desiData;
            $partQuantity = count($desiData) / 4;


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

                // echo $en . ' ' . $boy . ' ' . $yukseklik . ' ' . $agirlik;
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

                if ($desi > 300 || $agirlik > 100)
                    $heavyLoadCarryingStatus = true;

                if (count($desiKeys) == 0)
                    break;
            }

        }

        if (($cargoType != 'Dosya' && $cargoType != 'Mi') && $heavyLoadCarryingStatus == true && $request->parcaSayisi == 1) {
            $heavyLoadCarryingCost = GetSettingsVal('heavy_load_carrying_cost');
            $heavyLoadCarryingCost = $heavyLoadCarryingCost + (($heavyLoadCarryingCost * 18) / 100);
        } else
            $heavyLoadCarryingCost = 0;
        ##  New Heavy Load Carrying Coast Calc END


        $json = [
            'service_fee' => $json['service_fee'],
            'post_service_price' => $postServicePrice,
            'heavy_load_carrying_cost' => $heavyLoadCarryingCost,
            'mobile_service_fee' => $mobileServiceFee,
            'total_weight' => $totalAgirlik,
            'part_quantity' => $partQuantity,
            'total_desi' => $totalDesi,
            'total_volume' => $totalHacim,
        ];

        return $json;
    }
}
