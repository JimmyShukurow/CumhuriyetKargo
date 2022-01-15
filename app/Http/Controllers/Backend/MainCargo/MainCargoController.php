<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Http\Controllers\Controller;
use App\Models\AdditionalServices;
use App\Models\Agencies;
use App\Models\CargoAddServices;
use App\Models\CargoCancellationApplication;
use App\Models\Cargoes;
use App\Models\CargoMovements;
use App\Models\CargoPartDetails;
use App\Models\Cities;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\DesiList;
use App\Models\Districts;
use App\Models\FilePrice;
use App\Models\LocalLocation;
use App\Models\Receivers;
use App\Models\Settings;
use App\Models\SmsContent;
use App\Models\TransshipmentCenterDistricts;
use App\Models\TransshipmentCenters;
use App\Models\User;
use App\Notifications\TicketNotify;
use Brick\Math\Exception\DivisionByZeroException;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Faker\Provider\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class MainCargoController extends Controller
{
    public function searchCargo()
    {
        $data['cities'] = Cities::all();

        GeneralLog('Kargo sorgulama sayfası görüntülendi.');
        return view('backend.main_cargo.search_cargo.index', compact(['data']));
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

        ## daily report start
        $daily['package_count'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('cargo_type', '<>', 'Dosya-Mi')
            ->where('departure_agency_code', $agency->id)
            ->count();

        $daily['file_count'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('cargo_type', 'Dosya-Mi')
            ->where('departure_agency_code', $agency->id)
            ->count();

        $daily['total_cargo_count'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('departure_agency_code', $agency->id)
            ->count();

        $daily['total_desi'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('departure_agency_code', $agency->id)
            ->sum('desi');

        $daily['total_number_of_pieces'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('departure_agency_code', $agency->id)
            ->whereNotIn('cargo_type', ['Dosya', 'Mi'])
            ->sum('number_of_pieces');

        $daily['total_endorsement'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('departure_agency_code', $agency->id)
            ->sum('total_price');

        $daily['total_endorsement'] = round($daily['total_endorsement'], 2);
        ## daily report end

        $daily['total_desi'] = round($daily['total_desi'], 2);

        GeneralLog('Kargolar Ana Menü görüntülendi.');
        return view('backend.main_cargo.main.index', compact(['data', 'daily']));
    }

    public function newCargo()
    {
        $data['additional_service'] = AdditionalServices::all();
        $data['cities'] = Cities::all();

        ## get agency district
        $agency = Agencies::where('id', Auth::user()->agency_code)->first();

        $tc = TransshipmentCenterDistricts::where('city', $agency->city)
            ->where('district', $agency->district)
            ->first();

        $tc = TransshipmentCenters::find($tc->tc_id);

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

        # evrensel posta hizmetleri ücreti
        $postServicePercent = DB::table('settings')
            ->where('key', 'post_services_percent')
            ->first();
        $postServicePercent = $postServicePercent->value;

        $fee['postal_services_fee'] = ($fee['first_file_price'] * $postServicePercent) / 100;

        $totalFirst = 0;
        $totalFirstNoKDV = 0;
        $totalFirst += $fee['first_total'] + $fee['first_file_price'] + $fee['postal_services_fee'];
        $totalFirstNoKDV = $fee['first_total'] + $fee['first_file_price'] + $fee['postal_services_fee'];

        $fee['first_total'] = round($totalFirst + ((18 * $totalFirst) / 100), 2);
        $fee['first_total_no_kdv'] = $totalFirstNoKDV;

        $data['collectible_cargo'] = Settings::where('key', 'collectible_cargo')->first();

        GeneralLog('Kargo oluştur sayfası görüntülendi.');
        return view('backend.main_cargo.main.create', compact(['data', 'fee', 'agency', 'tc']));
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

                return response()->json(['status' => '1', 'price' => $desiPrice, 'distance' => $distance, 'distance_price' => $distancePrice, 'post_service_price' => $postServicePrice]);
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

                if (($cargoType != 'Dosya' && $cargoType != 'Mi') && $desi == 0) {
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
                            } else if ($cargoType == 'Mi') {
                                $filePrice = $currentPrice->mi_price;
                                $json = ['service_fee' => $filePrice];
                            } else if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
                                ## calc desi price
                                $desiPrice = 0;
                                if ($desi > 50) {
                                    $desiPrice = $currentPrice->d_46_50;
                                    $amountOfIncrease = $currentPrice->amount_of_increase;
                                    for ($i = 50; $i < $desi; $i++)
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
                            } else if ($cargoType == 'Mi') {
                                $filePrice = $currentPrice->mi_price;
                                $json = ['service_fee' => $filePrice];
                            } else if ($cargoType != 'Dosya' && $cargoType != 'Mi') {
                                ## calc desi price
                                $desiPrice = 0;
                                if ($desi > 50) {
                                    $desiPrice = $currentPrice->d_46_50;
                                    $amountOfIncrease = $currentPrice->amount_of_increase;
                                    for ($i = 50; $i < $desi; $i++)
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
                            } else if ($cargoType == 'Mi') {
                                $filePrice = $currentPrice->mi_price;
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

                        if ($desi > 100 || $agirlik > 300)
                            $heavyLoadCarryingStatus = true;

                        if (count($desiKeys) == 0)
                            break;
                    }

                }

                if (($cargoType != 'Dosya' && $cargoType != 'Mi') && $heavyLoadCarryingStatus == true && $request->partQuantity == 1) {
                    $heavyLoadCarryingCost = GetSettingsVal('heavy_load_carrying_cost');
                    $heavyLoadCarryingCost = $heavyLoadCarryingCost + (($heavyLoadCarryingCost * 18) / 100);
                } else
                    $heavyLoadCarryingCost = 0;
                ##  New Heavy Load Carrying Coast Calc END


                $json = [
                    'service_fee' => $json['service_fee'],
                    'post_service_price' => $postServicePrice,
                    'heavy_load_carrying_cost' => $heavyLoadCarryingCost,
                    'mobile_service_fee' => $mobileServiceFee
                ];

                return response()->json($json, 200);
                break;

            case 'CreateCargo':
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
                    'add_service_price' => $addServicePrice,
                    'post_service_price' => $postServicePrice,
                    'heavy_load_carrying_cost' => $heavyLoadCarryingCost,
                    'total_price' => $totalPrice,
                    'home_delivery' => $homeDelivery,
                    'pick_up_address' => $pickUpAddress,
                    'agency_delivery' => $homeDelivery == '1' ? '0' : '1',
                    'status_for_human' => 'HAZIRLANIYOR',
                    'transporter' => 'CK',
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

            case 'DistributionControl':

                if ($request->currentCode == '' || $request->receiverCode == '')
                    return response()
                        ->json([
                            'status' => 0,
                            'message' => 'Alıcı ve Gönderici cari kodu bilgileri zorunludur!'
                        ]);

                $currentCode = str_replace(' ', '', $request->currentCode);
                $receiverCode = str_replace(' ', '', $request->receiverCode);

                $receiver = Currents::where('current_code', $receiverCode)
                    ->first();

//                return $receiver->city . ' - ' . $receiver->district . ' - ' . $receiver->neighborhood;

                $control = DB::table('local_locations')
                    ->where('city', $receiver->city)
                    ->where('district', $receiver->district)
                    ->where('neighborhood', $receiver->neighborhood)
                    ->first();

                if ($control == null)
                    return response()
                        ->json([
                            'status' => 0,
                            'message' => 'Alıcı için dağıtım yapılmayan bölge: ' . $receiver->neighborhood
                        ]);

                $agency = DB::table('agencies')
                    ->where('id', $control->agency_code)
                    ->first();

                if ($agency->status == '0')
                    return response()
                        ->json([
                            'status' => 0,
                            'message' => 'Alıcı [' . $agency->agency_name . '] şube pasif olduğundan kargo kesimi gerçekleştiremezsiniz.'
                        ]);

                $tc = getTCofAgency($agency->id);

                if ($control->area_type == 'AB')
                    $control->area_type = 'Ana Bölge';
                else if ($control->area_type == 'MB')
                    $control->area_type = 'Mobil Bölge';

                $array = [
                    'status' => 1,
                    'arrival_agency' => $agency->agency_name . '-' . $agency->agency_code,
                    'arrival_tc' => $tc->tc_name,
                    'area_type' => $control->area_type,
                ];

                return response()
                    ->json($array, 200);
                break;

            # INDEX TRANSACTION START
            case 'GetCargoInfo':

                if ($request->invoice_number != null)
                    $data['cargo'] = Cargoes::where('invoice_number', $request->invoice_number)
                        ->first();
                else if ($request->tracking_number != null)
                    $data['cargo'] = Cargoes::where('tracking_no', str_replace(' ', '', $request->tracking_number))
                        ->first();
                else
                    $data['cargo'] = Cargoes::find($request->id);

                if ($data['cargo'] == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Kargo Bulunamadı!'], 200);

                $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);
                $data['cargo']->distance = getDotter($data['cargo']->distance);

                $data['cargo']->created_at = dateFormatForJsonOutput($data['cargo']->created_at);

                $data['sender'] = DB::table('currents')
                    ->select(['current_code', 'tckn', 'category'])
                    ->where('id', $data['cargo']->sender_id)
                    ->first();
                $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

                $data['movements'] = DB::table('cargo_movements')
                    ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
                    ->groupBy('group_id')
                    ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
                    ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['receiver'] = DB::table('currents')
                    ->select(['current_code', 'tckn', 'category'])
                    ->where('id', $data['cargo']->receiver_id)
                    ->first();

                $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

                $data['creator'] = DB::table('view_users_all_info')
                    ->select(['name_surname', 'display_name'])
                    ->where('id', $data['cargo']->creator_user_id)
                    ->first();

                $data['departure'] = DB::table('agencies')
                    ->select(['agency_code', 'agency_name', 'city', 'district'])
                    ->where('id', $data['cargo']->departure_agency_code)
                    ->first();

                $data['departure_tc'] = DB::table('transshipment_centers')
                    ->select(['city', 'tc_name'])
                    ->where('id', $data['cargo']->departure_tc_code)
                    ->first();

                $data['arrival'] = DB::table('agencies')
                    ->select(['agency_code', 'agency_name', 'city', 'district'])
                    ->where('id', $data['cargo']->arrival_agency_code)
                    ->first();

                $data['arrival_tc'] = DB::table('transshipment_centers')
                    ->select(['city', 'tc_name'])
                    ->where('id', $data['cargo']->arrival_tc_code)
                    ->first();

                $data['sms'] = DB::table('sent_sms')
                    ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result')
                    ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['add_services'] = DB::table('cargo_add_services')
                    ->select(['service_name', 'price'])
                    ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['part_details'] = DB::table('cargo_part_details')
                    ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $newPartDetais = [];
                foreach ($data['part_details'] as $key)
                    $newPartDetais[] = [
                        'cargo_id' => $key->cargo_id,
                        'created_at' => $key->created_at,
                        'cubic_meter_volume' => $key->cubic_meter_volume,
                        'desi' => $key->desi,
                        'height' => $key->height,
                        'id' => $key->id,
                        'part_no' => $key->part_no,
                        'size' => $key->size,
                        'tracking_no' => $key->tracking_no,
                        'updated_at' => $key->updated_at,
                        'weight' => $key->weight,
                        'width' => $key->width,
                        'barcode_no' => crypteTrackingNo(str_replace(' ', '', $data['cargo']->tracking_no) . ' ' . $key->part_no)
                    ];

                $data['part_details'] = $newPartDetais;

                $data['cancellation_applications'] = DB::table('view_cargo_cancellation_app_detail')
                    ->where('cargo_id', $data['cargo']->id)
                    ->get();

                $data['official_reports'] = DB::table('view_official_reports_general_info')
                    ->whereRaw("( cargo_invoice_number ='" . $data['cargo']->invoice_number . "' or  description like '%" . $data['cargo']->invoice_number . "%')")
                    ->get();

                $data['status'] = 1;

                return response()
                    ->json($data, 200);

                break;

            case 'GetMultipleCargoInfo':

                $idsString = substr($request->id, 0, strlen($request->id) - 1);
                $idsArray = explode(',', $idsString);

                $cargoes = [];
                $CargoPartCount = 0;

                foreach ($idsArray as $key) {

                    $data['cargo'] = Cargoes::find($key);

                    if ($data['cargo'] == null) {
                        $cargoes[] = ['Cargo Not Found!'];
                        continue;
                    }

                    $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);

                    $data['cargo']->distance = getDotter($data['cargo']->distance);

                    $data['sender'] = DB::table('currents')
                        ->select(['current_code', 'tckn', 'category'])
                        ->where('id', $data['cargo']->sender_id)
                        ->first();

                    $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

                    $data['movements'] = DB::table('cargo_movements')
                        ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
                        ->groupBy('group_id')
                        ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
                        ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
                        ->get();

                    $data['receiver'] = DB::table('currents')
                        ->select(['current_code', 'tckn', 'category'])
                        ->where('id', $data['cargo']->receiver_id)
                        ->first();

                    $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

                    $data['creator'] = DB::table('view_users_all_info')
                        ->select(['name_surname', 'display_name'])
                        ->where('id', $data['cargo']->creator_user_id)
                        ->first();

                    $data['departure'] = DB::table('agencies')
                        ->select(['agency_code', 'agency_name', 'city', 'district'])
                        ->where('id', $data['cargo']->departure_agency_code)
                        ->first();

                    $data['departure_tc'] = DB::table('transshipment_centers')
                        ->select(['city', 'tc_name'])
                        ->where('id', $data['cargo']->departure_tc_code)
                        ->first();

                    $data['arrival'] = DB::table('agencies')
                        ->select(['agency_code', 'agency_name', 'city', 'district'])
                        ->where('id', $data['cargo']->arrival_agency_code)
                        ->first();

                    $data['arrival_tc'] = DB::table('transshipment_centers')
                        ->select(['city', 'tc_name'])
                        ->where('id', $data['cargo']->arrival_tc_code)
                        ->first();

                    $data['sms'] = DB::table('sent_sms')
                        ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result')
                        ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
                        ->get();

                    $data['add_services'] = DB::table('cargo_add_services')
                        ->select(['service_name', 'price'])
                        ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                        ->get();

                    $data['part_details'] = DB::table('cargo_part_details')
                        ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                        ->get();

                    $CargoPartCount += DB::table('cargo_part_details')
                        ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                        ->count();

                    $newPartDetais = [];
                    foreach ($data['part_details'] as $key)
                        $newPartDetais[] = [
                            'cargo_id' => $key->cargo_id,
                            'created_at' => $key->created_at,
                            'cubic_meter_volume' => $key->cubic_meter_volume,
                            'desi' => $key->desi,
                            'height' => $key->height,
                            'id' => $key->id,
                            'part_no' => $key->part_no,
                            'size' => $key->size,
                            'tracking_no' => $key->tracking_no,
                            'updated_at' => $key->updated_at,
                            'weight' => $key->weight,
                            'width' => $key->width,
                            'barcode_no' => crypteTrackingNo(str_replace(' ', '', $data['cargo']->tracking_no) . ' ' . $key->part_no)
                        ];

                    $data['part_details'] = $newPartDetais;

                    $data['cancellation_applications'] = DB::table('view_cargo_cancellation_app_detail')
                        ->where('cargo_id', $data['cargo']->id)
                        ->get();

                    $cargoes[] = $data;
                }


                return response()
                    ->json(['cargoes' => $cargoes, 'total_count' => $CargoPartCount], 200);

                break;

            case 'GetCargoMovementDetails':

                $details = DB::table('cargo_movements')
                    ->where('group_id', $request->group_id)
                    ->get();

                return response()
                    ->json($details, 200);

            case 'GetMainDailySummery':

                $agency = Agencies::where('id', Auth::user()->agency_code)->first();

                ## daily report start
                $daily['package_count'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->whereNotIn('cargo_type', ['Dosya-Mi'])
                    ->where('departure_agency_code', $agency->id)
                    ->count();
                $daily['package_count'] = getDotter($daily['package_count']);

                $daily['file_count'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('cargo_type', 'Dosya-Mi')
                    ->where('departure_agency_code', $agency->id)
                    ->count();
                $daily['file_count'] = getDotter($daily['file_count']);

                $daily['total_cargo_count'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->count();
                $daily['total_cargo_count'] = getDotter($daily['total_cargo_count']);

                $daily['total_desi'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->sum('desi');
                $daily['total_desi'] = getDotter($daily['total_desi']);

                $daily['total_number_of_pieces'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->whereNotIn('cargo_type', ['Dosya', 'Mi'])
                    ->sum('number_of_pieces');
                $daily['total_number_of_pieces'] = getDotter($daily['total_number_of_pieces']);

                $daily['total_endorsement'] = DB::table('cargoes')
                    ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
                    ->whereRaw('deleted_at is null')
                    ->where('departure_agency_code', $agency->id)
                    ->sum('total_price');

                $daily['total_endorsement'] = getDotter(round($daily['total_endorsement'], 2));
                ## daily report end

                return response()
                    ->json($daily, 200);
                break;

            case 'MakeCargoCancellationApplication':

                $rules = [
                    'iptal_nedeni' => 'required',
                    'id' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails())
                    return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

                $agency = Agencies::find(Auth::user()->agency_code);
                $cargo = Cargoes::find($request->id);

                if ($cargo == null || $cargo->creator_agency_code != $agency->id)
                    return response()
                        ->json([
                            'status' => -1,
                            'message' => 'Kargo Bulunamadı!'
                        ], 200);

                $control = DB::table('cargo_cancellation_applications')
                    ->where('confirm', '0')
                    ->where('cargo_id', $request->id)
                    ->count();

                if ($control > 0)
                    return response()
                        ->json([
                            'status' => -1,
                            'message' => 'Bu kargo için oluşturulmuş sonuç bekleyen bir iptal başvurusu zaten var!'
                        ], 200);


                if ($cargo->status_for_human != 'HAZIRLANIYOR')
                    return response()
                        ->json([
                            'status' => -1,
                            'message' => 'Bu kargo okutma işlemi görmüş, iptal başvurusu yapamazsınız. Lütfen Destek & Ticket üzerinden sistem desteğe durumu iletiniz!'
                        ], 200);


                $insert = CargoCancellationApplication::create([
                    'cargo_id' => $cargo->id,
                    'user_id' => Auth::id(),
                    'application_reason' => $request->iptal_nedeni,
                    'confirm' => '0',
                ]);

                if ($insert)
                    return response()->json(['status' => 1], 200);
                else
                    return response()->json(['status' => -1, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'], 200);

                break;
            # INDEX TRANSACTION END

            case 'GetAllCargoInfo':

                $data['cargo'] = DB::table('cargoes')
                    ->where('id', $request->id)
                    ->first();


                if ($data['cargo'] == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Kargo Bulunamadı!'], 200);

                $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);
                $data['cargo']->distance = getDotter($data['cargo']->distance);

                $data['sender'] = DB::table('currents')
                    ->select(['current_code', 'tckn', 'category'])
                    ->where('id', $data['cargo']->sender_id)
                    ->first();
                $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

                $data['movements'] = DB::table('cargo_movements')
                    ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
                    ->groupBy('group_id')
                    ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
                    ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['receiver'] = DB::table('currents')
                    ->select(['current_code', 'tckn', 'category'])
                    ->where('id', $data['cargo']->receiver_id)
                    ->first();

                $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

                $data['creator'] = DB::table('view_users_all_info')
                    ->select(['name_surname', 'display_name'])
                    ->where('id', $data['cargo']->creator_user_id)
                    ->first();

                $data['departure'] = DB::table('agencies')
                    ->select(['agency_code', 'agency_name', 'city', 'district'])
                    ->where('id', $data['cargo']->departure_agency_code)
                    ->first();

                $data['departure_tc'] = DB::table('transshipment_centers')
                    ->select(['city', 'tc_name'])
                    ->where('id', $data['cargo']->departure_tc_code)
                    ->first();

                $data['arrival'] = DB::table('agencies')
                    ->select(['agency_code', 'agency_name', 'city', 'district'])
                    ->where('id', $data['cargo']->arrival_agency_code)
                    ->first();

                $data['arrival_tc'] = DB::table('transshipment_centers')
                    ->select(['city', 'tc_name'])
                    ->where('id', $data['cargo']->arrival_tc_code)
                    ->first();

                $data['sms'] = DB::table('sent_sms')
                    ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result')
                    ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['add_services'] = DB::table('cargo_add_services')
                    ->select(['service_name', 'price'])
                    ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['cancellation_applications'] = DB::table('view_cargo_cancellation_app_detail')
                    ->where('cargo_id', $data['cargo']->id)
                    ->get();

                $data['part_details'] = DB::table('cargo_part_details')
                    ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['official_reports'] = DB::table('view_official_reports_general_info')
                    ->whereRaw("( cargo_invoice_number ='" . $data['cargo']->invoice_number . "' or  description like '%" . $data['cargo']->invoice_number . "%')")
                    ->get();

                $data['status'] = 1;

                return response()
                    ->json($data, 200);

                break;

            case 'GetCancelledCargoInfo':

                $data['cargo'] = DB::table('cargoes')
                    ->where('id', $request->id)
                    ->whereRaw('deleted_at is not null')
                    ->first();

                if ($data['cargo'] == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Kargo Bulunamadı!'], 200);

                $data['cargo']->tracking_no = TrackingNumberDesign($data['cargo']->tracking_no);
                $data['cargo']->distance = getDotter($data['cargo']->distance);

                $data['sender'] = DB::table('currents')
                    ->select(['current_code', 'tckn', 'category'])
                    ->where('id', $data['cargo']->sender_id)
                    ->first();
                $data['sender']->current_code = CurrentCodeDesign($data['sender']->current_code);

                $data['movements'] = DB::table('cargo_movements')
                    ->selectRaw('cargo_movements.*, number_of_pieces,  cargo_movements.group_id as testmebitch, (SELECT Count(*) FROM cargo_movements where cargo_movements.group_id = testmebitch) as current_pieces')
                    ->groupBy('group_id')
                    ->join('cargoes', 'cargoes.tracking_no', '=', 'cargo_movements.ctn')
                    ->where('ctn', '=', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['receiver'] = DB::table('currents')
                    ->select(['current_code', 'tckn', 'category'])
                    ->where('id', $data['cargo']->receiver_id)
                    ->first();

                $data['receiver']->current_code = CurrentCodeDesign($data['receiver']->current_code);

                $data['creator'] = DB::table('view_users_all_info')
                    ->select(['name_surname', 'display_name'])
                    ->where('id', $data['cargo']->creator_user_id)
                    ->first();

                $data['departure'] = DB::table('agencies')
                    ->select(['agency_code', 'agency_name', 'city', 'district'])
                    ->where('id', $data['cargo']->departure_agency_code)
                    ->first();

                $data['departure_tc'] = DB::table('transshipment_centers')
                    ->select(['city', 'tc_name'])
                    ->where('id', $data['cargo']->departure_tc_code)
                    ->first();

                $data['arrival'] = DB::table('agencies')
                    ->select(['agency_code', 'agency_name', 'city', 'district'])
                    ->where('id', $data['cargo']->arrival_agency_code)
                    ->first();

                $data['arrival_tc'] = DB::table('transshipment_centers')
                    ->select(['city', 'tc_name'])
                    ->where('id', $data['cargo']->arrival_tc_code)
                    ->first();

                $data['sms'] = DB::table('sent_sms')
                    ->select('id', 'heading', 'subject', 'phone', 'sms_content', 'result')
                    ->where('ctn', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['add_services'] = DB::table('cargo_add_services')
                    ->select(['service_name', 'price'])
                    ->where('cargo_tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $data['cancellation_applications'] = DB::table('view_cargo_cancellation_app_detail')
                    ->where('cargo_id', $data['cargo']->id)
                    ->get();

                $data['part_details'] = DB::table('cargo_part_details')
                    ->where('tracking_no', str_replace(' ', '', $data['cargo']->tracking_no))
                    ->get();

                $newPartDetais = [];
                foreach ($data['part_details'] as $key)
                    $newPartDetais[] = [
                        'cargo_id' => $key->cargo_id,
                        'created_at' => $key->created_at,
                        'cubic_meter_volume' => $key->cubic_meter_volume,
                        'desi' => $key->desi,
                        'height' => $key->height,
                        'id' => $key->id,
                        'part_no' => $key->part_no,
                        'size' => $key->size,
                        'tracking_no' => $key->tracking_no,
                        'updated_at' => $key->updated_at,
                        'weight' => $key->weight,
                        'width' => $key->width,
                        'barcode_no' => crypteTrackingNo(str_replace(' ', '', $data['cargo']->tracking_no) . ' ' . $key->part_no)
                    ];

                $data['part_details'] = $newPartDetais;

                $data['official_reports'] = DB::table('view_official_reports_general_info')
                    ->whereRaw("( cargo_invoice_number ='" . $data['cargo']->invoice_number . "' or  description like '%" . $data['cargo']->invoice_number . "%')")
                    ->get();

                $data['status'] = 1;

                return response()
                    ->json($data, 200);

                break;

            default:
                return 'no -case';
                break;

        }
        return 0;
    }

    public function getMainCargoes(Request $request)
    {
        $agency = Agencies::where('id', Auth::user()->agency_code)->first();

        $finishDate = $request->finishDate;
        $startDate = $request->startDate;
        $cargoType = $request->cargoType;
        $trackingNo = str_replace([' ', '_'], [''], $request->trackingNo);
        $cargoContent = $request->cargoContent;
        $invoiceNumber = $request->invoice_number;
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
            ->select(['cargoes.*', 'users.name_surname'])
            ->whereRaw($cargoType ? "cargo_type='" . $cargoType . "'" : '1 > 0')
            ->whereRaw($cargoContent ? "cargo_content='" . $cargoContent . "'" : '1 > 0')
            ->whereRaw($collectible ? "collectible='" . $collectible . "'" : '1 > 0')
            ->whereRaw($currentCity ? "sender_city='" . $currentCity . "'" : '1 > 0')
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($receiverCode ? 'current_code=' . $receiverCode : '1 > 0')
            ->whereRaw($trackingNo ? 'tracking_no=' . $trackingNo : '1 > 0')
            ->whereRaw($invoiceNumber ? "invoice_number='" . $invoiceNumber . "'" : '1 > 0')
            ->whereRaw($currentName ? "sender_name='" . $currentName . "'" : '1 > 0')
            ->whereRaw($paymentType ? "payment_type='" . $paymentType . "'" : '1 > 0')
            ->whereRaw($receiverCity ? "receiver_city='" . $receiverCity . "'" : '1 > 0')
            ->whereRaw($receiverName ? "receiver_name='" . $receiverName . "'" : '1 > 0')
            ->whereRaw($status ? "cargoes.status='" . $status . "'" : '1 > 0')
            ->whereRaw($statusForHuman ? "cargoes.status_for_human='" . $statusForHuman . "'" : '1 > 0')
            ->whereRaw($system ? "system='" . $system . "'" : '1 > 0')
            ->whereRaw($record == 1 ? "cargoes.deleted_at is null" : 'cargoes.deleted_at is not null')
            ->whereRaw("cargoes.created_at between '" . $startDate . "'  and '" . $finishDate . "'")
            ->whereRaw($transporter ? "transporter='" . $transporter . "'" : '1 > 0')
            ->where('departure_agency_code', $agency->id);

        return datatables()->of($cargoes)
            ->setRowId(function ($cargoes) {
                return "cargo-item-" . $cargoes->id;
            })
//            ->editColumn('invoice_number', function ($cargoes) {
//                return '<b class="text-dark">' . $cargoes->invoice_number . '</b>';
//            })
            ->editColumn('payment_type', function ($cargoes) {
                return $cargoes->payment_type == 'Gönderici Ödemeli' ? '<b class="text-alternate">' . 'GÖ' . '</b>' : '<b class="text-dark">' . 'AÖ' . '</b>';
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
            ->editColumn('free_btn', function ($t) {
                return '';
            })
            ->editColumn('check', function ($t) {
                return '<span class="unselectable">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
            })
            ->addColumn('tracking_no', 'backend.main_cargo.main.columns.tracking_no')
            ->addColumn('invoice_number', 'backend.main_cargo.main.columns.invoice_number')
            ->addColumn('edit', 'backend.main_cargo.main.columns.edit')
            ->rawColumns(['edit', 'tracking_no', 'invoice_number', 'check', 'status_for_human', 'total_price', 'collectible', 'payment_type', 'collection_fee', 'status', 'name_surname', 'created_at'])
            ->make(true);
    }

    public function getGlobalCargoes(Request $request)
    {
        $trackingNo = str_replace([' ', '_'], ['', ''], $request->trackingNo);
        $invoiceNumber = $request->invoiceNumber;
        $cargoType = $request->cargoType;
        $currentCity = $request->senderCity;
        $currentCode = str_replace([' ', '_'], ['', ''], $request->senderCurrentCode);
        $receiverCurrentCode = str_replace([' ', '_'], ['', ''], $request->receiverCurrentCode);
        $currentName = $request->senderName;
        $receiverCity = $request->receiverCity;
        $receiverName = tr_strtoupper($request->receiverName);
        $receiverDistrict = $request->receiverDistrict;
        $receiverPhone = $request->receiverPhone;
        $currentDistrict = $request->senderDistrict;
        $currentPhone = $request->senderPhone;
        $finishDate = $request->finishDate;
        $startDate = $request->startDate;
        $filterByDAte = $request->filterByDAte;

        $finishDate = new Carbon($finishDate);
        $startDate = new Carbon($startDate);

        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 30) {
                    return response()->json([], 509);
                }
            }
        }

        if ($currentDistrict) {
            $district = Districts::find($currentDistrict);
            $currentDistrict = $district->district_name;
        } else
            $currentDistrict = false;

        if ($receiverDistrict) {
            $district = Districts::find($receiverDistrict);
            $receiverDistrict = $district->district_name;
        } else
            $receiverDistrict = false;

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
            ->whereRaw($invoiceNumber ? "invoice_number='" . $invoiceNumber . "'" : '1 > 0')
            ->whereRaw($currentName ? "sender_name like '" . $currentName . "%'" : '1 > 0')
            ->whereRaw($receiverCity ? "receiver_city='" . $receiverCity . "'" : '1 > 0')
            ->whereRaw($receiverPhone ? "receiver_phone='" . $receiverPhone . "'" : '1 > 0')
            ->whereRaw($currentPhone ? "sender_phone='" . $currentPhone . "'" : '1 > 0')
            ->whereRaw($receiverDistrict ? "receiver_district='" . $receiverDistrict . "'" : '1 > 0')
            ->whereRaw($receiverName ? "receiver_name like '%" . $receiverName . "%'" : '1 > 0')
            ->whereRaw($filterByDAte == "true" ? "cargoes.created_at between '" . $startDate . "'  and '" . $finishDate . "'" : '1 > 0')
            ->whereRaw('cargoes.deleted_at is null')
            ->limit(100)
            ->orderByDesc('created_at')
            ->get();

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
            ->setRowId(function ($cargoes) {
                return "cargo-item-" . $cargoes->id;
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
                return $cargoes->agency_name;
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
            ->editColumn('created_at', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->created_at . '</b>';
            })
            ->editColumn('status_for_human', function ($cargoes) {
                return '<b class="text-success">' . $cargoes->status_for_human . '</b>';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->addColumn('tracking_no', 'backend.main_cargo.search_cargo.columns.tracking_no')
            ->addColumn('invoice_number', 'backend.main_cargo.main.columns.invoice_number')
            ->rawColumns(['tracking_no', 'invoice_number', 'agency_name', 'status_for_human', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }

    public function statementOfResponsibility($ctn)
    {
        $ctn = str_replace(' ', '', $ctn);

        $templateProccessor = new TemplateProcessor('backend/word-template/StatementOfResposibility.docx');

        $cargo = Cargoes::where('tracking_no', $ctn)->first();
        $sender = Currents::find($cargo->sender_id);

        $templateProccessor
            ->setValue('date', date('d / m / Y'));
        $templateProccessor
            ->setValue('name', $cargo->sender_name);
        $templateProccessor
            ->setValue('tckn', $sender->tckn);
        $templateProccessor
            ->setValue('phone', $cargo->sender_phone);
        $templateProccessor
            ->setValue('address', $cargo->sender_address);
        $templateProccessor
            ->setValue('ctn', TrackingNumberDesign($cargo->tracking_no));

        $fileName = 'ST-' . substr($cargo->sender_name, 0, 30) . '.docx';

        $templateProccessor
            ->saveAs($fileName);

        return response()
            ->download($fileName)
            ->deleteFileAfterSend(true);
    }

    public function cancelledCargoesIndex()
    {
        $data['cities'] = Cities::all();

        GeneralLog('İptal edilen kargolar sayfası görüntülendi.');
        return view('backend.main_cargo.cancelled_cargoes.index', compact(['data']));
    }

    public function getCancelledCargoes(Request $request)
    {
        $trackingNo = str_replace([' ', '_'], ['', ''], $request->trackingNo);
        $invoiceNumber = $request->invoiceNumber;
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

        $finishDate = new Carbon($finishDate);
        $startDate = new Carbon($startDate);


        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 30) {
                    return response()->json([], 509);
                }
            }
        }


        if ($currentDistrict) {
            $district = Districts::find($currentDistrict);
            $currentDistrict = $district->district_name;
        } else
            $currentDistrict = false;

        if ($receiverDistrict) {
            $district = Districts::find($receiverDistrict);
            $receiverDistrict = $district->district_name;
        } else
            $receiverDistrict = false;

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
            ->whereRaw($invoiceNumber ? "invoice_number='" . $invoiceNumber . "'" : '1 > 0')
            ->whereRaw($currentName ? "sender_name like '" . $currentName . "%'" : '1 > 0')
            ->whereRaw($receiverCity ? "receiver_city='" . $receiverCity . "'" : '1 > 0')
            ->whereRaw($receiverPhone ? "receiver_phone='" . $receiverPhone . "'" : '1 > 0')
            ->whereRaw($currentPhone ? "sender_phone='" . $currentPhone . "'" : '1 > 0')
            ->whereRaw($receiverDistrict ? "receiver_district='" . $receiverDistrict . "'" : '1 > 0')
            ->whereRaw($receiverName ? "receiver_name like '%" . $receiverName . "%'" : '1 > 0')
            ->whereRaw($filterByDAte == "true" ? "cargoes.created_at between '" . $startDate . "'  and '" . $finishDate . "'" : '1 > 0')
            ->whereRaw('cargoes.deleted_at is not null')
            ->where('cargoes.departure_agency_code', Auth::user()->agency_code)
            ->limit(100)
            ->orderByDesc('created_at')
            ->get();

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
            ->setRowId(function ($cargoes) {
                return "cargo-item-" . $cargoes->id;
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
                return $cargoes->agency_name;
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
            ->editColumn('created_at', function ($cargoes) {
                return '<b class="text-primary">' . $cargoes->created_at . '</b>';
            })
            ->editColumn('status_for_human', function ($cargoes) {
                return '<b class="text-success">' . $cargoes->status_for_human . '</b>';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->addColumn('tracking_no', 'backend.main_cargo.search_cargo.columns.tracking_no')
            ->addColumn('invoice_number', 'backend.main_cargo.main.columns.invoice_number')
            ->rawColumns(['tracking_no', 'invoice_number', 'agency_name', 'status_for_human', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }


}












