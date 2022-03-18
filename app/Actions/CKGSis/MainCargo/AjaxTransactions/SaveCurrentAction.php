<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Actions\CKGSis\Customer\AjaxTransaction\GetTaxOfficeWithCodeAction;
use App\Models\Currents;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Rules\CityDistrictNeighborhoodRule;
use App\Rules\PhoneControlRule;
use App\Rules\TcknConfirmlRule;
use App\Rules\VknConfirmlRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Rule;

class SaveCurrentAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'kategori' => ['required', Rule::in(['Bireysel', 'Kurumsal'])],
            'telefon' => ['nullable', new PhoneControlRule()],
            'cep_telefonu' => ['required', new PhoneControlRule()],
            'email' => 'nullable|email:rfc,dns',
            'il' => 'required|numeric',
            'ilce' => 'required|numeric',
            'mahalle' => ['required', 'numeric', new CityDistrictNeighborhoodRule($request->il, $request->ilce, $request->mahalle)],
            'bina_no' => 'required',
            'daire_no' => 'required',
            'kat_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);

        if ($request->kategori == 'Bireysel') {

            $rules = [
                'ad' => 'required',
                'soyad' => 'required',
                'tc' => 'required',
                'dogum_tarihi' => ['required', 'numeric', new TcknConfirmlRule($request->ad, $request->soyad, $request->dogum_tarihi, $request->tc)],
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);

        } else if ($request->kategori == 'Kurumsal') {

            $rules = [
                'vkn' => 'required',
                'vd_sehir' => 'required',
                'vergi_dairesi' => ['required', new VknConfirmlRule($request->vkn, $request->vd_sehir, $request->vergi_dairesi)],
                'yetkili_adi' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails())
                return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);

        }

//        return response()->json(['status' => '0', 'message' => 'asd'], 200);


        $current_code = CreateNewCurrentNumber();
        $neighborhood = Neighborhoods::with('district.city')->where('id', $request->mahalle)->first();

        $taxOffice = "";
        if ($request->kategori == 'Bireysel') {
            $name = tr_strtoupper($request->ad) . ' ' . tr_strtoupper($request->soyad);
        } else if ($request->kategori == 'Kurumsal') {
            $result = vkn_confirm($request->vkn, $request->vergi_dairesi, $request->vd_sehir);
            $name = $result['data']->unvan;
            $taxOffice = GetTaxOfficeWithCodeAction::run($request->vd_sehir, $request->vergi_dairesi);
        }

        $insert = Currents::create([
            'current_type' => 'Gönderici',
            'current_code' => $current_code,
            'category' => $request->kategori,
            'tckn' => $request->tc,
            'vkn' => $request->vkn,
            'name' => $name,
            'authorized_name' => tr_strtoupper($request->yetkili_adi),
            'tax_administration' => $taxOffice,
            'phone' => $request->telefon,
            'gsm' => $request->cep_telefonu,
            'email' => $request->email,
            'city' => tr_strtoupper($neighborhood->district->city->city_name),
            'district' => tr_strtoupper($neighborhood->district->district_name),
            'neighborhood' => tr_strtoupper($neighborhood->neighborhood_name),
            'street' => tr_strtoupper($request->cadde),
            'street2' => tr_strtoupper($request->sokak),
            'building_no' => tr_strtoupper($request->bina_no),
            'door_no' => tr_strtoupper($request->daire_no),
            'floor' => tr_strtoupper($request->kat_no),
            'address_note' => tr_strtoupper($request->adres_notu),
            'agency' => Auth::user()->agency_code,
            'created_by_user_id' => Auth::id()
        ]);

        if ($insert)
            return response()
                ->json(array(
                    'status' => 1,
                    'message' => 'Gönderici başarıyla kaydedildi',
                    'current_code' => $current_code,
                ), 200);
        else
            return response()
                ->json(array(
                    'status' => 0,
                    'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!'
                ), 200);
    }
}
