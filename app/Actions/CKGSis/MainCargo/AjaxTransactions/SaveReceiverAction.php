<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Currents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use App\Rules\NameSurname\CurrentNameControlRule;

class SaveReceiverAction
{
    use AsAction;

    public function handle($request)
    {
        # => validete
        $rules = [
            'kategori' => 'required|in:Bireysel,Kurumsal',
            'telefon' => 'nullable',
            'cep_telefonu' => 'required',
            'email' => 'nullable|email:rfc,dns',
            'il' => 'required|numeric',
            'ilce' => 'required|numeric',
            'mahalle_koy' => 'required|numeric',
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

        # => validete2
        if ($request->kategori == 'Bireysel') {

            if (strlen($request->tckn) < 11 && $request->tckn != '')
                return response()->json([
                    'status' => '0',
                    'message' => 'Alıcı TCKN 11 haneli olmalıdır!'
                ], 200);

            $rules = ['ad' => ['required', new CurrentNameControlRule()], 'soyad' => ['required', new CurrentNameControlRule()]];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => '-1',
                    'errors' => $validator->getMessageBag()->toArray()
                ], 200);
            }

        } else if ($request->kategori == 'Kurumsal') {

            if ($request->vkn != '' && strlen($request->vkn) < 10) {
                return response()->json([
                    'status' => '0',
                    'message' => 'Alıcı VKN 10 haneli olmalıdır!'
                ], 200);
            }

            $rules = ['firma_unvani' => 'required'];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => '-1',
                    'errors' => $validator->getMessageBag()->toArray()
                ], 200);
            }
        }

        # => cadde sokak kontrolü
        if ($request->cadde == '' && $request->sokak == '')
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Cadde ve Sokak alanlarından en az bir tanesi zorunludur!'
                ], 200);

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


        $cityDistrictNeighborhood = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle_koy))
            ->exists();

        if ($cityDistrictNeighborhood == null) {
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Geçerli bir il, ilçe ve mahalle seçiniz!'
                ], 200);
        }

        $cityDistrictNeighborhood = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle_koy))
            ->get();

        $codeControl = true;
        $current_code = '';

        # => create current code and code control
        while ($codeControl != false) {
            $current_code = rand(111111111, 999999999);
            $codeControl = DB::table('currents')
                ->where('current_code', $current_code)
                ->exists();
        }
        $nameSurnameCompany = $request->kategori == 'Bireysel' ? tr_strtoupper($request->ad) . ' ' . tr_strtoupper($request->soyad) : tr_strtoupper($request->firma_unvani);

        ### => insert transactionF
        $insert = Currents::create([
            'current_type' => 'Alıcı',
            'current_code' => $current_code,
            'category' => $request->kategori,
            'tckn' => $request->kategori == 'Bireysel' ? $request->tckn : $request->vkn,
            'name' => $nameSurnameCompany,
            'authorized_name' => $request->kategori == 'Kurumsal' ? tr_strtoupper($request->ad . " " . $request->soyad) : '',
            'phone' => $request->telefon,
            'gsm' => $request->cep_telefonu,
            'email' => $request->email,
            'city' => $cityDistrictNeighborhood[0]->city_name,
            'district' => $cityDistrictNeighborhood[0]->district_name,
            'neighborhood' => $cityDistrictNeighborhood[0]->neighborhood_name,
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
                    'message' => 'Alıcı başarıyla kaydedildi!',
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
