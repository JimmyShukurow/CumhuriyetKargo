<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Currents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class SaveCurrentAction
{
    use AsAction;

    public function handle($request)
    {
         # => validete
         $rules = [
            'ad' => 'required',
            'soyad' => 'required',
            'tc' => 'required',
            'dogum_tarihi' => 'required|numeric',
            'telefon' => 'nullable',
            'cep_telefonu' => 'required',
            'email' => 'nullable|email:rfc,dns',
            'il' => 'required|numeric',
            'ilce' => 'required|numeric',
            'mahalle' => 'required|numeric',
            'bina_no' => 'required',
            'daire_no' => 'required',
            'kat_no' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => '-1',
                'errors' => $validator->getMessageBag()->toArray()
            ], 200);
        }

        $gsm = CharacterCleaner($request->cep_telefonu);
        if (strlen($gsm) != 10)
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Cep telefonu alanı başında 0 olmadan 10 hane olacak şekilde girilmelidir!'
                ], 200);


        $phone = CharacterCleaner($request->telefon);
        if (strlen($phone) != 10 && $phone != '')
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Telefon alanı başında 0 olmadan 10 hane olacak şekilde girilmelidir, boş bırakın veya doğru olduğundan emin olun!'
                ], 200);


        $cityDistrict = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle))
            ->exists();

        if ($cityDistrict == null) {
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Geçerli bir il ilçe ve mahalle seçiniz!'
                ], 200);
        }

        $cityDistrict = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle))
            ->get();

        ## confirm tc
        $bilgiler = array(
            "isim" => $request->ad,
            "soyisim" => $request->soyad,
            "dogumyili" => $request->dogum_tarihi,
            "tcno" => $request->tc,
        );

        $sonuc = tcno_dogrula($bilgiler);

        if ($sonuc == "false")
            return response()
                ->json(array(
                    'status' => 0,
                    'message' => 'Kişinin kimlik bilgileri hatalıdır! Lütfen TC, Ad, Soyad ve Doğum Yılı bilgilerini kontrol ediniz.'),
                    200);

        $codeControl = true;
        $current_code = '';

        # => create current code and code control
        while ($codeControl != false) {
            $current_code = rand(111111111, 999999999);
            $codeControl = DB::table('currents')
                ->where('current_code', $current_code)
                ->exists();
        }

        ### => insert transaction
        $insert = Currents::create([
            'current_type' => 'Gönderici',
            'current_code' => $current_code,
            'category' => 'Bireysel',
            'tckn' => $request->tc,
            'name' => tr_strtoupper($request->ad) . ' ' . tr_strtoupper($request->soyad),
            'phone' => $request->telefon,
            'gsm' => $request->cep_telefonu,
            'email' => $request->email,
            'city' => $cityDistrict[0]->city_name,
            'district' => $cityDistrict[0]->district_name,
            'street' => tr_strtoupper($request->cadde),
            'street2' => tr_strtoupper($request->sokak),
            'neighborhood' => $cityDistrict[0]->neighborhood_name,
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
