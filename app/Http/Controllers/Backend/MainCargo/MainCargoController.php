<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Http\Controllers\Controller;
use App\Models\AdditionalServices;
use App\Models\Agencies;
use App\Models\CargoAddServices;
use App\Models\Cargoes;
use App\Models\CargoPartDetails;
use App\Models\Cities;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\DesiList;
use App\Models\Districts;
use App\Models\FilePrice;
use App\Models\Receivers;
use App\Models\Settings;
use App\Models\SmsContent;
use App\Models\User;
use Carbon\Carbon;
use Faker\Provider\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MainCargoController extends Controller
{
    public function searchCargo()
    {
        $data['cities'] = Cities::all();
        return view('backend.serach-cargo.index', compact(['data']));
    }

    public function index()
    {
        $data['agencies'] = Agencies::all();
        $data['gm_users'] = DB::table('users')
            ->where('agency_code', 1)
            ->get();

        ## get agency district
        $agency = Agencies::where('id', Auth::user()->agency_code)->first();
        $data['districts'] = DB::table('view_city_districts')
            ->where('city_name', $agency->city)
            ->get();

        $data['user_district'] = $agency->district;
        $data['user_city'] = $agency->city;
        $data['status'] = $status = DB::table('cargoes')
            ->select('status')->groupBy('status')->get();
        $data['status_for_human'] = $status = DB::table('cargoes')
            ->select('status_for_human')->groupBy('status_for_human')->get();
        $data['transporters'] = $status = DB::table('cargoes')
            ->select('transporter')->groupBy('transporter')->get();
        $data['systems'] = $status = DB::table('cargoes')
            ->select('system')->groupBy('system')->get();
        $data['cargo_contents'] = $status = DB::table('cargoes')
            ->select('cargo_content')->groupBy('cargo_content')->get();
        $data['cargo_types'] = $status = DB::table('cargoes')
            ->select('cargo_type')->groupBy('cargo_type')->get();
        $data['agency_users'] = User::where('agency_code', Auth::user()->agency_code)->get();
        $data['cities'] = Cities::all();


        return view('backend.main_cargo.index', compact(['data']));
    }

    public function newCargo()
    {
        $data['additional_service'] = AdditionalServices::all();
        $data['cities'] = Cities::all();

        ## get agency district
        $agency = Agencies::where('id', Auth::user()->agency_code)->first();
        $data['districts'] = DB::table('view_city_districts')
            ->where('city_name', $agency->city)
            ->get();
        $data['neighborhoods'] = DB::table('view_city_district_neighborhoods')
            ->where('city_name', $agency->city)
            ->where('district_name', $agency->district)
            ->get();
        $data['user_neighborhood'] = $agency->neighborhood;
        $data['user_district'] = $agency->district;
        $data['user_city'] = $agency->city;

        $fee['first_add_service'] = DB::table('additional_services')
            ->where('default', '=', '1')
            ->sum('price');

        $fee['first_total'] = DB::table('additional_services')
            ->where('default', '=', '1')
            ->sum('price');

        $filePrice = FilePrice::first();
        $fee['first_file_price'] = $filePrice->individual_file_price;

        $totalFirst = 0;
        $totalFirstNoKDV = 0;
        $totalFirst += $fee['first_total'] + $fee['first_file_price'];
        $totalFirstNoKDV = $fee['first_total'] + $fee['first_file_price'];

        $fee['first_total'] = $totalFirst + ((18 * $totalFirst) / 100);
        $fee['first_total_no_kdv'] = $totalFirstNoKDV;

        $data['collectible_cargo'] = Settings::where('key', 'collectible_cargo')->first();

        return view('backend.main_cargo.create', compact(['data', 'fee']));
    }

    public function ajaxTransacrtions(Request $request, $transaction)
    {
        switch ($transaction) {

            case 'SaveCurrent':
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
                break;

            case 'SaveReceiver':
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

                    $rules = ['ad' => 'required', 'soyad' => 'required'];
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
                break;

            case 'ConfirmTC':
                $bilgiler = array(
                    "isim" => $request->ad,
                    "soyisim" => $request->soyad,
                    "dogumyili" => $request->dogum_tarihi,
                    "tcno" => $request->tc,
                );

                $sonuc = tcno_dogrula($bilgiler);

                if ($sonuc == "true")
                    return response()
                        ->json(array('status' => 1), 200);
                else
                    return response()
                        ->json(array(
                            'status' => 0,
                            'message' => 'Bilgiler hatalıdır! Lütfen TC, Ad, Soyad ve Doğum Yılı bilgilerini kontrol ediniz.'),
                            200);
                break;

            case 'GetCustomer':

                $request->currentCode = str_replace(' ', '', $request->currentCode);
                if ($request->type == 'receiver')
                    $customer = Currents::where('current_code', $request->currentCode)
                        ->select(['name', 'current_type', 'category', 'tckn', 'vkn', 'current_code', 'gsm', 'address_note', 'street', 'street2', 'city', 'district', 'neighborhood', 'building_no', 'door_no', 'floor', 'address_note'])
                        ->where('confirmed', '1')
                        ->first();

                else if ($request->type == 'current')
                    $customer = Currents::where('current_code', $request->currentCode)
                        ->select(['name', 'current_type', 'category', 'tckn', 'vkn', 'current_code', 'gsm', 'address_note', 'street', 'street2', 'city', 'district', 'neighborhood', 'building_no', 'door_no', 'floor', 'address_note'])
                        ->where('confirmed', '1')
                        ->first();

                if ($customer == null)
                    return response()
                        ->json(['status' => -1], 200);

                return response()
                    ->json($customer, 200);
                break;

            case 'GetCustomers':
                $Customers = DB::table('currents')
                    ->where('name', 'like', '%' . $request->name . '%')
                    ->where('confirmed', '1')
                    ->limit(200)
                    ->orderBy('created_at', 'desc')
                    ->get(['current_type', 'category', 'name', 'gsm', 'city', 'district', 'neighborhood', 'street', 'street2', 'building_no', 'door_no', 'floor', 'address_note', 'current_code as id', 'created_at as reg_date']);

                return response()
                    ->json($Customers, 200);
                break;

            case 'GetCurrents':
                $request->currentSearchTerm = tr_strtoupper(tr_strtolower(enCharacters(urlCharacters($request->currentSearchTerm))));

                $Currents = DB::table('currents')
                    ->where('name', 'like', '%' . $request->currentSearchTerm . '%')
                    ->whereRaw('deleted_at is null')
                    ->where('confirmed', '1')
                    ->where('current_type', 'Gönderici')
                    ->limit(50)
                    ->orderBy('name')
                    ->distinct()
                    ->get(['name']);

                return response()
                    ->json($Currents, 200);
                break;

            case 'GetReceivers':
                $request->currentSearchTerm = tr_strtoupper(tr_strtolower(enCharacters(urlCharacters($request->currentSearchTerm))));

                $Currents = DB::table('currents')
                    ->where('name', 'like', '%' . $request->currentSearchTerm . '%')
                    ->whereRaw('deleted_at is null')
                    ->where('confirmed', '1')
                    ->limit(50)
                    ->orderBy('name')
                    ->distinct()
                    ->get(['name']);

                return response()
                    ->json($Currents, 200);
                break;

            case 'GetDistance':
                $startPoint = tr_strtoupper($request->startPoint);
                $endPoint = tr_strtoupper($request->endPoint);

                if ($startPoint == $endPoint)
                    $resposne = ['status' => 1, 'distance' => 0, 'price' => 0];
                else {
                    ## get plaque
                    $startPoint = Cities::where('city_name', $startPoint)->first('plaque');
                    $endPoint = Cities::where('city_name', $endPoint)->first('plaque');

                    $json = json_decode(distances(), true);
                    $distance = $json[$startPoint->plaque][$endPoint->plaque];

                    # => calculate distance price <= #
                    $distancePrice = calcDistancePrice($distance);

                    $resposne = ['status' => 1, 'distance' => getDotter($distance), 'price' => $distancePrice];
                }

                return response()->json($resposne, 200);
                break;

            case 'CalcDesiPrice':

                $desi = $request->desi;
                $distance = 0;
                if ($desi == 0)
                    return response()->json(['status' => '-1', 'message' => 'Ücrete Esas Ağırlık (Desi) 0\'dan büyük olmalıdır!']);

                if ($desi > 0 && $desi < 1)
                    $desi = 1;

                $startPoint = tr_strtoupper($request->startPoint);
                $endPoint = tr_strtoupper($request->endPoint);
                if ($startPoint == $endPoint)
                    $distancePrice = 0;
                else {
                    ## get plaque
                    $startPoint = Cities::where('city_name', $startPoint)->first('plaque');
                    $endPoint = Cities::where('city_name', $endPoint)->first('plaque');#
                    $json = json_decode(distances(), true);
                    $distance = $json[$startPoint->plaque][$endPoint->plaque];
                    # => calculate distance price <= #
                    $distancePrice = calcDistancePrice($distance);
                }

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
                return response()->json(['status' => '1', 'price' => $desiPrice, 'distance' => $distance, 'distance_price' => $distancePrice]);
                break;

            case 'GetFilePrice':

                $distance = 0;
                $startPoint = tr_strtoupper($request->startPoint);
                $endPoint = tr_strtoupper($request->endPoint);
                if ($startPoint == $endPoint)
                    $distancePrice = 0;
                else {
                    ## get plaque
                    $startPoint = Cities::where('city_name', $startPoint)->first('plaque');
                    $endPoint = Cities::where('city_name', $endPoint)->first('plaque');#
                    $json = json_decode(distances(), true);
                    $distance = $json[$startPoint->plaque][$endPoint->plaque];
                    # => calculate distance price <= #
                    $distancePrice = calcDistancePrice($distance);
                }

                $filePrice = FilePrice::first();
                $filePrice = $filePrice->individual_file_price;

                return response()->json(['status' => '1', 'price' => $filePrice, 'distance' => $distance, 'distance_price' => $distancePrice]);

                break;

            case 'GetPriceForCustomers':


                $desi = $request->desi;
                $cargoType = $request->cargoType;
                $currentCode = str_replace(' ', '', $request->currentCode);
                $receiverCode = str_replace(' ', '', $request->receiverCode);
                $paymenyType = $request->paymentType;

                $current = Currents::where('current_code', $currentCode)->first();
                $receiver = Currents::where('current_code', $receiverCode)->first();

                $currentType = $current->current_type;
                $currentCategory = $current->category;

                $receiverType = $receiver->current_type;
                $receiverCategory = $receiver->category;

                if ($cargoType == 'Koli' && $desi == 0) {
                    $desiPrice = 0;
                    $json = ['service_fee' => $desiPrice];
                } else {


                    if (($currentType == 'Gönderici' && $currentCategory == 'Kurumsal') || ($receiverType == 'Gönderici' && $receiverCategory == 'Kurumsal')) {
                        # => contracted / En az 1 Kurumsal

                        # Gönderici Kurumsal - Alıcı Bireysel
                        if ($currentCategory == 'Kurumsal' && $receiverCategory == 'Bireysel') {
                            # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                            $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                            if ($cargoType == 'Dosya') {
                                $filePrice = $currentPrice->file_price;
                                $json = ['service_fee' => $filePrice];
                            } else if ($cargoType == 'Koli') {
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

                        # Gönderici Bireysel - Alıcı Kurumsal
                        if ($currentCategory == 'Bireysel' && $receiverCategory == 'Kurumsal') {
                            # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                            $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();
                            if ($cargoType == 'Dosya') {
                                $filePrice = $currentPrice->file_price;
                                $json = ['service_fee' => $filePrice];
                            } else if ($cargoType == 'Koli') {
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

                        # Gönderici Kurumsal - Alıcı Kurumsal
                        if ($currentCategory == 'Kurumsal' && $receiverCategory == 'Kurumsal') {
                            # ===> Ödeme Taraflı Cari Anlaşmalı Fiyat Standart Fiyat

                            # ===> Gönderici Ödemeli
                            if ($paymenyType == 'Gönderici Ödemeli')
                                $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                            # ===> Alıcı Ödemeli
                            else if ($paymenyType == 'Alıcı Ödemeli')
                                $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();


                            if ($cargoType == 'Dosya') {
                                $filePrice = $currentPrice->file_price;
                                $json = ['service_fee' => $filePrice];
                            } else if ($cargoType == 'Koli') {
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
                        } else if ($cargoType == 'Koli') {

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

                            $json = ['service_fee' => $desiPrice];
                        }
                    }
                }
                return response()->json($json, 200);
                break;

            case 'CreateCargo':
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
                    'genelToplam' => 'required',
                    'totalHacim' => 'required',
                    'kargoIcerigi' => 'required',
                ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);


                if ($request->gonderiTuru == 'Koli' && $request->desi == 0)
                    return response()
                        ->json(['status' => -1, 'message' => 'Lütfen koli için desi bilgisi giriniz!'], 200);

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


                if ($request->tahsilatliKargo == 'true' && $permission_collectible_cargo->value == '0')
                    return response()->json(['status' => -1, 'message' => 'Tahsilatlı kargo Türkiye geneli pasif durumda, tahsilatlı kargo çıkaramazsınız!'], 200);

                if ($request->tahsilatliKargo == 'true') {
                    if ($currentType != 'Gönderici' || $currentCategory != 'Kurumsal')
                        return response()->json(['status' => -1, 'message' => 'Yalnızca Kurumsal-Anlaşmalı cariler tahsilatlı kargo çıkartabilir.'], 200);
                }

                ## customers controle
                $currentState = CurrentControl($current->current_code);
                $receiverState = CurrentControl($receiver->current_code);

                if ($currentState['status'] != 1)
                    return response()
                        ->json(['status' => -1, 'message' => 'Gönderici hatalı!' . $currentState['result']], 200);

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

                if ($request->ekHizmetFiyat != $addServicePrice)
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
                        } else if ($cargoType == 'Koli') {
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

                    # Gönderici Bireysel - Alıcı Kurumsal
                    if ($currentCategory == 'Bireysel' && $receiverCategory == 'Kurumsal') {
                        # ===> Cari Anlaşmalı Fiyat Standart Fiyat
                        $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();
                        if ($cargoType == 'Dosya') {
                            $filePrice = $currentPrice->file_price;
                            $serviceFee = $filePrice;
                        } else if ($cargoType == 'Koli') {
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

                    # Gönderici Kurumsal - Alıcı Kurumsal
                    if ($currentCategory == 'Kurumsal' && $receiverCategory == 'Kurumsal') {
                        # ===> Ödeme Taraflı Cari Anlaşmalı Fiyat Standart Fiyat

                        # ===> Gönderici Ödemeli
                        if ($paymenyType == 'Gönderici Ödemeli')
                            $currentPrice = CurrentPrices::where('current_code', $currentCode)->first();

                        # ===> Alıcı Ödemeli
                        else if ($paymenyType == 'Alıcı Ödemeli')
                            $currentPrice = CurrentPrices::where('current_code', $receiverCode)->first();


                        if ($cargoType == 'Dosya') {
                            $filePrice = $currentPrice->file_price;
                            $serviceFee = $filePrice;
                        } else if ($cargoType == 'Koli') {
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
                    } else if ($cargoType == 'Koli') {

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

                if (!(compareFloatEquality($request->hizmetUcreti, $serviceFee)))
                    return response()
                        ->json(['status' => -1, 'message' => 'Hizmet tutarları eşleşmiyor, lütfen sayfayı yenileyip tekrar deneyiniz!'], 200);


                # Control Parts Of Cargo
                if ($cargoType == 'Koli') {
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

                $currentAddress = AddressMaker($current->city, $current->district, $current->neighborhood, $current->street, $current->street2, $current->building_no, $current->floor, $current->door_no, $current->address_note);
                $receiverAddress = AddressMaker($receiver->city, $receiver->district, $receiver->neighborhood, $receiver->street, $receiver->street2, $receiver->building_no, $receiver->floor, $receiver->door_no, $receiver->address_note);
                $departureAgency = Agencies::find(Auth::user()->agency_code);

                ## calc total price
                $totalPriceExceptKdv = $distancePrice + $addServicePrice + $serviceFee;
                $kdvPrice = $totalPriceExceptKdv * 0.18;
                $kdvPrice = round($kdvPrice, 2);
                $totalPrice = $totalPriceExceptKdv + $kdvPrice;

                $collection = collect(array_keys($addServices));
                $homeDelivery = $collection->contains('add-service-8') ? '1' : '0';

                $collection = collect(array_keys($addServices));
                $pickUpAddress = $collection->contains('add-service-21') ? '1' : '0';

                $ctn = CreateCargoTrackingNo(Auth::user()->agency_code);

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
                    'number_of_pieces' => $cargoType == 'Koli' ? $partQuantity : 1,
                    'cargo_type' => $cargoType,
                    'cargo_content' => tr_strtoupper($request->kargoIcerigi),
                    'cargo_content_ex' => tr_strtoupper($request->kargoIcerigiAciklama),

                    'tracking_no' => $ctn,
                    'arrival_city' => $receiver->city,
                    'arrival_district' => $receiver->district,

                    'arrival_agency_code' => '31',
                    'arrival_tc_code' => '31',

                    'departure_city' => $current->city,
                    'departure_district' => $current->district,
                    'departure_agency_code' => Auth::user()->agency_code,
                    'departure_tc_code' => $departureAgency->transshipment_center_code,
                    'creator_agency_code' => Auth::user()->agency_code,
                    'creator_user_id' => Auth::id(),
                    'status' => 'İRSALİYE KESİLDİ',
                    'collectible' => $request->tahsilatliKargo == 'true' ? '1' : '0',
                    'collection_fee' => $request->tahsilatliKargo == 'true' ? getDoubleValue($request->faturaTutari) : 0,
                    'collection_payment_type' => $request->tahsilatliKargo == 'true' ? 'Nakit' : '0',
                    'desi' => $cargoType == 'Koli' ? $totalDesi : 0,
                    'kdv_percent' => 18,
                    'cubic_meter_volume' => $cargoType == 'Koli' ? $totalHacim : 0,
                    'kdv_price' => $kdvPrice,
                    'distance_price' => $distancePrice,
                    'service_price' => $serviceFee,
                    'add_service_price' => $addServicePrice,
                    'total_price' => $totalPrice,
                    'home_delivery' => $homeDelivery,
                    'pick_up_address' => $pickUpAddress,
                    'agency_delivery' => $homeDelivery == '1' ? '0' : '1',
                    'status_for_human' => 'HAZIRLANIYOR',
                    'transporter' => 'CK',
                    'system' => 'CKG-Sis',
                ]);

                if ($CreateCargo) {

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

                    if ($cargoType == 'Koli') {

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
                    }

                    if ($insert) {

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
                break;

            case 'CalcCollectionPercent':

                $price = doubleval(getDoubleValue($request->collectionPrice));
                $interruption = 0;

                if ($request->currentCode == '')
                    return response()
                        ->json(['status' => -1, 'message' => 'Kesinti Oranını hesaplamak için öncelikle göndericiyi ve alıcıyı girin.'], 200);

                $current = Currents::where('current_code', str_replace(' ', '', $request->currentCode))->first();

                if ($current === null)
                    return response()
                        ->json(['status' => -1, 'message' => 'Gönderici bulunamadı, cari kodunun doğru olduğundan emin olun!'], 200);

                $state = CurrentControl($current->current_code);

                if ($state['status'] != 1)
                    return response()
                        ->json(['status' => -1, 'message' => 'Gönderici hatalı! ' . $state['result']], 200);

                if ($current->category == 'Kurumsal' && $current->current_type == 'Gönderici') {

                    $currentPrices = CurrentPrices::where('current_code', $current->current_code)->first();

                    if ($price > 0 && $price <= 200)
                        $interruption = $currentPrices->collect_price;
                    else if ($price == 0)
                        $interruption = 0;
                    else {
                        $interruptionPercent = $currentPrices->collect_amount_of_increase;
                        $interruption = ($price * $interruptionPercent) / 100;
                    }

                    return response()
                        ->json(['status' => 1, 'interruption' => '₺ ' . $interruption, 'to_be_paid' => '₺ ' . ($price - $interruption)], 200);

//                    return $interruption;
//                    dd($currentPrices);


                } else
                    return response()
                        ->json(['status' => -1, 'message' => 'Göndericinin anlaşması yok, tahsilatlı kargo çıkaramazsınız!'], 200);

                return $request->all();

                break;

            default:
                return 'no -case';
                break;

        }
        return 0;
    }

    public function getMainCargoes(Request $request)
    {
        $finishDate = $request->finishDate;
        $startDate = $request->startDate;
        $cargoType = $request->cargoType;
        $trackingNo = str_replace([' ', '_'], [''], $request->trackingNo);
        $cargoContent = $request->cargoContent;
        $collectible = $request->collectible;
        $currentCity = $request->currentCity;
        $currentCode = str_replace([' ', '_'], ['', ''], $request->currentCode);
        $receiverCode = str_replace([' ', '_'], ['', ''], $request->receiverCode);
        $cargoType = $request->cargoType;
        $currentName = $request->currentName;
        $paymentType = $request->paymentType;
        $receiverCity = $request->receiverCity;
        $receiverName = $request->receiverName;
        $record = $request->record;
        $status = $request->status;
        $statusForHuman = $request->statusForHuman;
        $system = $request->system;
        $transporter = $request->transporter;

        $category = $request->category != -1 ? $request->category : '';

        $cargoes = DB::table('cargoes')
            ->join('users', 'users.id', '=', 'cargoes.creator_user_id')
            ->join('currents', 'currents.id', '=', 'cargoes.sender_id')
//            ->join('currents', 'currents.id', '=', 'cargoes.receiver_id')
            ->select(['cargoes.*', 'users.name_surname'])
            ->whereRaw($cargoType ? "cargo_type='" . $cargoType . "'" : '1 > 0')
            ->whereRaw($cargoContent ? "cargo_content='" . $cargoContent . "'" : '1 > 0')
            ->whereRaw($collectible ? "collectible='" . $collectible . "'" : '1 > 0')
            ->whereRaw($currentCity ? "sender_city='" . $currentCity . "'" : '1 > 0')
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($receiverCode ? 'current_code=' . $receiverCode : '1 > 0')
            ->whereRaw($trackingNo ? 'tracking_no=' . $trackingNo : '1 > 0')
            ->whereRaw($currentName ? "sender_name='" . $currentName . "'" : '1 > 0')
            ->whereRaw($paymentType ? "payment_type='" . $paymentType . "'" : '1 > 0')
            ->whereRaw($receiverCity ? "receiver_city='" . $receiverCity . "'" : '1 > 0')
            ->whereRaw($receiverName ? "receiver_name='" . $receiverName . "'" : '1 > 0')
            ->whereRaw($status ? "cargoes.status='" . $status . "'" : '1 > 0')
            ->whereRaw($statusForHuman ? "cargoes.status_for_human='" . $statusForHuman . "'" : '1 > 0')
            ->whereRaw($system ? "system='" . $system . "'" : '1 > 0')
            ->whereRaw($record == 1 ? "cargoes.deleted_at is null" : 'cargoes.deleted_at is not null')
            ->whereRaw("cargoes.created_at between '" . $startDate . "'  and '" . $finishDate . "'")
            ->whereRaw($transporter ? "transporter='" . $transporter . "'" : '1 > 0');

        return datatables()->of($cargoes)
            ->setRowId(function ($cargoes) {
                return "cargo-item-" . $cargoes->id;
            })
            ->editColumn('tracking_no', function ($cargoes) {
                return TrackingNumberDesign($cargoes->tracking_no);
            })
            ->editColumn('payment_type', function ($cargoes) {
                return $cargoes->payment_type == 'Gönderici Ödemeli' ? '<b class="text-alternate">' . $cargoes->payment_type . '</b>' : '<b class="text-dark">' . $cargoes->payment_type . '</b>';
            })
            ->editColumn('receiver_address', function ($cargoes) {
                return substr($cargoes->receiver_address, 0, 30);
            })
            ->editColumn('sender_name', function ($cargoes) {
                return substr($cargoes->sender_name, 0, 30);
            })
            ->editColumn('receiver_name', function ($cargoes) {
                return substr($cargoes->receiver_name, 0, 30);
            })
            ->editColumn('collectible', function ($cargoes) {
                return $cargoes->collectible == '1' ? '<b class="text-success">Evet</b>' : '<b class="text-danger">Hayır</b>';
            })
            ->editColumn('total_price', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->total_price . '₺' . '</b>';
            })
            ->editColumn('collection_fee', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->collection_fee . '₺' . '</b>';
            })
            ->editColumn('status', function ($cargoes) {
                return '<b class="text-dark">' . $cargoes->status . '</b>';
            })
            ->editColumn('name_surname', function ($cargoes) {
                return '<b class="text-dark">' . $cargoes->name_surname . '</b>';
            })
            ->editColumn('created_at', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->created_at . '</b>';
            })
            ->editColumn('status_for_human', function ($cargoes) {
                return '<b class="text-success">' . $cargoes->status_for_human . '</b>';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->rawColumns(['edit', 'status_for_human', 'total_price', 'collectible', 'payment_type', 'collection_fee', 'status', 'name_surname', 'created_at'])
            ->make(true);
    }


    public function getGlobalCargoes(Request $request)
    {
        $trackingNo = str_replace([' ', '_'], ['', ''], $request->trackingNo);
        $cargoType = $request->cargoType;
        $currentCity = $request->senderCity;
        $currentCode = str_replace([' ', '_'], ['', ''], $request->senderCurrentCode);
        $receiverCurrentCode = str_replace([' ', '_'], ['', ''], $request->receiverCurrentCode);
        $currentName = $request->senderName;
        $receiverCity = $request->receiverCity;
        $receiverName = $request->receiverName;

        $receiverDistrict = $request->receiverDistrict;
        $receiverPhone = $request->receiverPhone;
        $currentDistrict = $request->senderDistrict;
        $currentPhone = $request->senderPhone;

        $finishDate = $request->finishDate;
        $startDate = $request->startDate;
        $filterByDAte = $request->filterByDAte;

        $finishDate=new Carbon($finishDate);
        $startDate=new Carbon($startDate);

        $diff=$startDate->diffInDays($finishDate);

        if($filterByDAte){
            if ($diff >=60){
                return response()->json([], 509);
            }
        }





        if ($currentDistrict) {
            $district = Districts::find($currentDistrict);
            $currentDistrict = $district->district_name;
        } else {
            $currentDistrict = false;
        }

        if ($receiverDistrict) {
            $district = Districts::find($receiverDistrict);
            $receiverDistrict = $district->district_name;
        } else {
            $receiverDistrict = false;
        }


        $cargoes = DB::table('cargoes')
            ->join('users', 'users.id', '=', 'cargoes.creator_user_id')
            ->join('agencies', 'agencies.id', '=', 'users.agency_code')
            ->select(['cargoes.*', 'agencies.city as city_name', 'agencies.district as district_name', 'agencies.agency_name', 'users.name_surname as user_name_surname'])
            ->whereRaw($cargoType ? "cargo_type='" . $cargoType . "'" : '1 > 0')
            ->whereRaw($currentCity ? "sender_city='" . $currentCity . "'" : '1 > 0')
            ->whereRaw($currentDistrict ? "sender_district='" . $currentDistrict . "'" : '1 > 0')
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($receiverCurrentCode ? 'current_code=' . $receiverCurrentCode : '1 > 0')
            ->whereRaw($trackingNo ? 'tracking_no=' . $trackingNo : '1 > 0')
            ->whereRaw($currentName ? "sender_name like '" . $currentName . "%'" : '1 > 0')
            ->whereRaw($receiverCity ? "receiver_city='" . $receiverCity . "'" : '1 > 0')
            ->whereRaw($receiverPhone ? "receiver_phone='" . $receiverPhone . "'" : '1 > 0')
            ->whereRaw($currentPhone ? "sender_phone='" . $currentPhone . "'" : '1 > 0')
            ->whereRaw($receiverDistrict ? "receiver_district='" . $receiverDistrict . "'" : '1 > 0')
            ->whereRaw($receiverName ? "receiver_name like '%" . $receiverName . "%'" : '1 > 0')
            ->whereRaw('cargoes.deleted_at is null');


        return datatables()->of($cargoes)
            ->setRowId(function ($cargoes) {
                return "cargo-item-" . $cargoes->id;
            })
            ->editColumn('tracking_no', function ($cargoes) {
                return TrackingNumberDesign($cargoes->tracking_no);
            })
            ->editColumn('payment_type', function ($cargoes) {
                return $cargoes->payment_type == 'Gönderici Ödemeli' ? '<b class="text-alternate">' . $cargoes->payment_type . '</b>' : '<b class="text-dark">' . $cargoes->payment_type . '</b>';
            })
            ->editColumn('cargo_type', function ($cargoes) {
                return $cargoes->cargo_type == 'Koli' ? '<b class="text-primary">' . $cargoes->cargo_type . '</b>' : '<b class="text-success">' . $cargoes->cargo_type . '</b>';
            })
            ->editColumn('receiver_address', function ($cargoes) {
                return substr($cargoes->receiver_address, 0, 30);
            })
            ->editColumn('agency_name', function ($cargoes) {
                return $cargoes->city_name . '/' . $cargoes->district_name . '-' . $cargoes->agency_name . ' <b class="text-primary">(' . $cargoes->user_name_surname . ')</b>';
            })
            ->editColumn('sender_name', function ($cargoes) {
                return substr($cargoes->sender_name, 0, 30);
            })
            ->editColumn('receiver_name', function ($cargoes) {
                return substr($cargoes->receiver_name, 0, 30);
            })
            ->editColumn('collectible', function ($cargoes) {
                return $cargoes->collectible == '1' ? '<b class="text-success">Evet</b>' : '<b class="text-danger">Hayır</b>';
            })
            ->editColumn('total_price', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->total_price . '₺' . '</b>';
            })
            ->editColumn('collection_fee', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->collection_fee . '₺' . '</b>';
            })
            ->editColumn('status', function ($cargoes) {
                return '<b class="text-dark">' . $cargoes->status . '</b>';
            })
//            ->editColumn('name_surname', function ($cargoes) {
//                return '<b class="text-dark">' . $cargoes->name_surname . '</b>';
//            })
            ->editColumn('created_at', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->created_at . '</b>';
            })
            ->editColumn('status_for_human', function ($cargoes) {
                return '<b class="text-success">' . $cargoes->status_for_human . '</b>';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->rawColumns(['edit', 'cargo_type', 'agency_name', 'status_for_human', 'total_price', 'collectible', 'payment_type', 'collection_fee', 'status', 'name_surname', 'created_at'])
            ->make(true);
    }

}












