<?php

namespace App\Actions\CKGSis\Customer\AjaxTransaction;

use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Rules\AgencyControl;
use App\Rules\CityDistrictNeighborhoodRule;
use App\Rules\CityDistrictRule;
use App\Rules\PriceControl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAgencyContractedCustomerAction
{
    use AsAction;

    public function handle($request)
    {
        $request->validate([
            'adSoyadFirma' => 'required',
            'vergiDairesi' => 'required',
            'vknTckn' => 'required|numeric',
            'telefon' => 'required',
            'il' => 'required|numeric',
            'ilce' => 'required|numeric',
            'mahalle' => ['required', 'numeric', new CityDistrictNeighborhoodRule($request->il, $request->ilce, $request->mahalle)],
            'bina_no' => 'required',
            'kat_no' => 'required',
            'daire_no' => 'required',
            'gsm' => 'required',
            'iban' => 'required',
            'hesapSahibiTamIsim' => 'required',
            'sevkIl' => 'required|numeric',
            'sevkIlce' => ['required', 'numeric', new CityDistrictRule($request->sevkIl, $request->sevkIlce)],
            'sevkAdres' => 'required',
            'sozlesmeBaslangicTarihi' => 'required',
            'sozlesmeBitisTarihi' => 'required',
            'dosyaUcreti' => ['required', new PriceControl],
            'miUcreti' => ['required', new PriceControl],
            'd1_5' => ['required', new PriceControl],
            'd6_10' => ['required', new PriceControl],
            'd11_15' => ['required', new PriceControl],
            'd16_20' => ['required', new PriceControl],
            'd21_25' => ['required', new PriceControl],
            'd26_30' => ['required', new PriceControl],
            'ustuDesi' => ['required', new PriceControl],
            'priceDraft' => 'required',
            'cadde' => 'nullable',
            'sokak' => 'nullable',
        ]);

        # => cadde sokak kontrolü
        if ($request->cadde == '' && $request->sokak == '') {
            $request->flash();
            return back()->with('error', 'Cadde ve Sokak alanlarından en az bir tanesi zorunludur!');
        }

        $codeControl = true;
        $current_code = '';

        # => create current code and code control
        while ($codeControl != false) {
            $current_code = rand(111111111, 999999999);
            $codeControl = DB::table('currents')
                ->where('current_code', $current_code)
                ->exists();
        }

        $neighborhood = Neighborhoods::with('district.city')->where('id', $request->mahalle)->first();
        $dispatchDistrict = Districts::with('city')->where('id', $request->sevkIlce)->first();

        DB::beginTransaction();
        try {
            ### => insert transaction
            $insert = Currents::create([
                'current_type' => 'Gönderici',
                'current_code' => $current_code,
                'category' => 'Anlaşmalı',
                'name' => tr_strtoupper($request->adSoyadFirma),
                'tax_administration' => tr_strtoupper($request->vergiDairesi),
                'tckn' => $request->vknTckn,
                'city' => tr_strtoupper($neighborhood->district->city->city_name),
                'district' => tr_strtoupper($neighborhood->district->district_name),
                'neighborhood' => tr_strtoupper($neighborhood->neighborhood_name),
                'street' => tr_strtoupper($request->cadde),
                'street2' => tr_strtoupper($request->sokak),
                'building_no' => tr_strtoupper($request->bina_no),
                'door_no' => tr_strtoupper($request->daire_no),
                'floor' => tr_strtoupper($request->kat_no),
                'address_note' => tr_strtoupper($request->adres_notu),
                'phone' => $request->telefon,
                'phone2' => $request->telefon2,
                'gsm' => $request->gsm,
                'gsm2' => $request->gsm2,
                'email' => $request->email,
                'web_site' => $request->website,
                'dispatch_city' => tr_strtoupper($dispatchDistrict->city->city_name),
                'dispatch_district' => tr_strtoupper($dispatchDistrict->district_name),
                'dispatch_post_code' => $request->sevkPostaKodu,
                'dispatch_adress' => tr_strtoupper($request->sevkAdres),
                'status' => '1',
                'agency' => Auth::user()->agency_code,
                'bank_iban' => $request->iban,
                'bank_owner_name' => tr_strtoupper($request->hesapSahibiTamIsim),
                'discount' => getDoubleValue($request->iskonto),
                'reference' => tr_strtoupper($request->referans),
                'confirmed' => '0',
                'created_by_user_id' => Auth::id(),
                'contract_start_date' => $request->sozlesmeBaslangicTarihi,
                'contract_end_date' => $request->sozlesmeBitisTarihi,
                'mb_status' => '1',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $request->flash();
            return back()
                ->with('error', 'Cari kayıt işlemi esnasında bir hata oluştu!  err' . $e->getMessage());
        }

        try {
            $create = CurrentPrices::create([
                'price_draft_id' => $request->priceDraft,
                'current_code' => $current_code,
                'file' => getDoubleValue($request->dosyaUcreti),
                'mi' => getDoubleValue($request->miUcreti),
                'd_1_5' => getDoubleValue($request->d1_5),
                'd_6_10' => getDoubleValue($request->d6_10),
                'd_11_15' => getDoubleValue($request->d11_15),
                'd_16_20' => getDoubleValue($request->d16_20),
                'd_21_25' => getDoubleValue($request->d21_25),
                'd_26_30' => getDoubleValue($request->d26_30),
                'amount_of_increase' => getDoubleValue($request->ustuDesi),
                'collect_price' => 0,
                'collect_amount_of_increase' => 0,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $request->flash();
            return back()
                ->with('error', 'Cari fiyat kayıt işlemi esnasında bir hata oluştu!');
        }

        DB::commit();
        GeneralLog("Acente tarafından " . $current_code . " kodlu kusumsal cari oluşturuldu.");
        return back()->with('success', 'Cari oluşturuldu, Onay Bekliyor!');
    }
}
