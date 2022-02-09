<?php

namespace App\Http\Controllers\Backend\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\Roles;
use App\Models\TransshipmentCenters;
use App\Models\User;
use App\Notifications\GeneralNotify;
use App\Rules\AgencyControl;
use App\Rules\PriceControl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Collection;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Spatie\Activitylog\Models\Activity;
use function GuzzleHttp\Promise\all;
use PhpOffice\PhpWord\Writer\PDF;

class SenderCurrentController extends Controller
{
    public function index()
    {
        $data['agencies'] = Agencies::all();
        $data['gm_users'] = DB::table('users')
            ->where('agency_code', 1)
            ->get();

        GeneralLog('Pazarlama-Gönderici Cariler sayfası görüntülendi!');

        return view('backend.marketing.sender_currents.index', compact('data'));
    }


    public function create()
    {
        $data['cities'] = Cities::all();
        return view('backend.marketing.sender_currents.create', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'adSoyadFirma' => 'required',
            'acente' => ['required', new AgencyControl],
            'vergiDairesi' => 'required',
            'vknTckn' => 'required|numeric',
            'telefon' => 'required',
            'il' => 'required|numeric',
            'ilce' => 'required|numeric',
            'mahalle' => 'required|numeric',
            'bina_no' => 'required',
            'kat_no' => 'required',
            'daire_no' => 'required',
            'gsm' => 'required',
            'iban' => 'required',
            'hesapSahibiTamIsim' => 'required',
            'sevkIl' => 'required|numeric',
            'sevkIlce' => 'required|numeric',
            'sevkAdres' => 'required',
            'sozlesmeBaslangicTarihi' => 'required',
            'sozlesmeBitisTarihi' => 'required',
            'tahsilatEkHizmetBedeli' => 'required',
            'tahsilatEkHizmetBedeli200Ustu' => 'required',
            'dosyaUcreti' => ['required', new PriceControl],
            'miUcreti' => ['required', new PriceControl],
            'd1_5' => ['required', new PriceControl],
            'd6_10' => ['required', new PriceControl],
            'd11_15' => ['required', new PriceControl],
            'd16_20' => ['required', new PriceControl],
            'd21_25' => ['required', new PriceControl],
            'd26_30' => ['required', new PriceControl],
            'd31_35' => ['required', new PriceControl],
            'd36_40' => ['required', new PriceControl],
            'd41_45' => ['required', new PriceControl],
            'd46_50' => ['required', new PriceControl],
            'ustuDesi' => ['required', new PriceControl],
            'mbStatus' => 'required|in:1,0'
        ]);

        $cityDistrict = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle))
            ->exists();
        if ($cityDistrict == null) {
            $request->flash();
            return back()->with('error', 'Lütfen geçerli bir il, ilçe ve mahalle seçinizi');
        }
        $cityDistrict = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle))
            ->get();

        #dispatch city-district control
        $dispatchCityDistrict = DB::table('view_city_districts')
            ->where('city_id', intval($request->sevkIl))
            ->where('district_id', intval($request->sevkIlce))
            ->exists();
        if ($dispatchCityDistrict == null) {
            $request->flash();
            return back()->with('error', 'Lütfen geçerli bir il-ilçe seçinizi');
        }
        $dispatchCityDistrict = DB::table('view_city_districts')
            ->where('city_id', intval($request->sevkIl))
            ->where('district_id', intval($request->sevkIlce))
            ->get();

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

        $date = \Carbon\Carbon::createFromDate(date('Y'));
        $endOfYear = \Carbon\Carbon::parse(date('Y-m-d'))->endOfYear();


        ### => insert transaction
        $insert = Currents::create([
            'current_type' => 'Gönderici',
            'current_code' => $current_code,
            'category' => 'Kurumsal',
            'name' => tr_strtoupper($request->adSoyadFirma),
            'tax_administration' => tr_strtoupper($request->vergiDairesi),
            'tckn' => $request->vknTckn,
            'city' => tr_strtoupper($cityDistrict[0]->city_name),
            'district' => tr_strtoupper($cityDistrict[0]->district_name),
            'neighborhood' => tr_strtoupper($cityDistrict[0]->neighborhood_name),
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
            'dispatch_city' => tr_strtoupper($dispatchCityDistrict[0]->city_name),
            'dispatch_district' => tr_strtoupper($dispatchCityDistrict[0]->district_name),
            'dispatch_post_code' => $request->sevkPostaKodu,
            'dispatch_adress' => tr_strtoupper($request->sevkAdres),
            'status' => '1',
            'agency' => $request->acente,
            'bank_iban' => $request->iban,
            'bank_owner_name' => tr_strtoupper($request->hesapSahibiTamIsim),
            'discount' => getDoubleValue($request->iskonto),
            'reference' => tr_strtoupper($request->referans),
            'confirmed' => '0',
            'created_by_user_id' => Auth::id(),
            'contract_start_date' => $request->sozlesmeBaslangicTarihi,
            'contract_end_date' => $endOfYear,
            'mb_status' => $request->mbStatus,
        ]);

        if ($insert) {

            $create = CurrentPrices::create([
                'current_code' => $current_code,
                'file_price' => getDoubleValue($request->dosyaUcreti),
                'mi_price' => getDoubleValue($request->miUcreti),
                'd_1_5' => getDoubleValue($request->d1_5),
                'd_6_10' => getDoubleValue($request->d6_10),
                'd_11_15' => getDoubleValue($request->d11_15),
                'd_16_20' => getDoubleValue($request->d16_20),
                'd_21_25' => getDoubleValue($request->d21_25),
                'd_26_30' => getDoubleValue($request->d26_30),
                'd_31_35' => getDoubleValue($request->d31_35),
                'd_36_40' => getDoubleValue($request->d36_40),
                'd_41_45' => getDoubleValue($request->d41_45),
                'd_46_50' => getDoubleValue($request->d46_50),
                'amount_of_increase' => getDoubleValue($request->ustuDesi),
                'collect_price' => getDoubleValue($request->tahsilatEkHizmetBedeli),
                'collect_amount_of_increase' => getDoubleValue($request->tahsilatEkHizmetBedeli200Ustu),
            ]);

            if ($create) {
                GeneralLog($current_code . " kodlu kusumsal cari oluşturuldu.");
                return back()->with('success', 'Cari oluşturuldu, Onay Bekliyor!');
            } else {
                $request->flash();
                return back()->with('success', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
            }

        }

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data['cities'] = Cities::all();
        $current = Currents::find($id);
        $price = CurrentPrices::where('current_code', $current->current_code)->first();
        $data['districts'] = DB::table('view_city_districts')
            ->where('city_name', $current->city)
            ->get();

        $data['neighborhoods'] = DB::table('view_city_district_neighborhoods')
            ->where('city_name', $current->city)
            ->where('district_name', $current->district)
            ->get();


        $data['dispatch_districts'] = DB::table('view_city_districts')
            ->where('city_name', $current->dispatch_city)
            ->get();
        $agency = Agencies::find($current->agency);

        return view('backend.marketing.sender_currents.edit', compact(['data', 'current', 'price', 'agency']));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'adSoyadFirma' => 'required',
            'acente' => ['required', new AgencyControl],
            'vergiDairesi' => 'required',
            'vknTckn' => 'required|numeric',
            'telefon' => 'required',
            'il' => 'required|numeric',
            'ilce' => 'required|numeric',
            'mahalle' => 'required|numeric',
            'bina_no' => 'required',
            'kat_no' => 'required',
            'daire_no' => 'required',
            'gsm' => 'required',
            'iban' => 'required',
            'hesapSahibiTamIsim' => 'required',
            'sevkIl' => 'required|numeric',
            'sevkIlce' => 'required|numeric',
            'sevkAdres' => 'required',
            'sozlesmeBaslangicTarihi' => 'required',
            'sozlesmeBitisTarihi' => 'required',
            'tahsilatEkHizmetBedeli' => 'required',
            'tahsilatEkHizmetBedeli200Ustu' => 'required',
            'dosyaUcreti' => ['required', new PriceControl],
            'miUcreti' => ['required', new PriceControl],
            'd1_5' => ['required', new PriceControl],
            'd6_10' => ['required', new PriceControl],
            'd11_15' => ['required', new PriceControl],
            'd16_20' => ['required', new PriceControl],
            'd21_25' => ['required', new PriceControl],
            'd26_30' => ['required', new PriceControl],
            'd31_35' => ['required', new PriceControl],
            'd36_40' => ['required', new PriceControl],
            'd41_45' => ['required', new PriceControl],
            'd46_50' => ['required', new PriceControl],
            'ustuDesi' => ['required', new PriceControl],
            'mbStatus' => 'required|in:1,0'
        ]);

        $cityDistrict = DB::table('view_city_district_neighborhoods')
            ->where('city_id', $request->il)
            ->where('district_id', $request->ilce)
            ->where('neighborhood_id', $request->mahalle)
            ->exists();
        if ($cityDistrict == null) {
            $request->flash();
            return back()->with('error', 'Lütfen geçerli bir il, ilçe ve mahalle seçinizi');
        }
        $cityDistrict = DB::table('view_city_district_neighborhoods')
            ->where('city_id', intval($request->il))
            ->where('district_id', intval($request->ilce))
            ->where('neighborhood_id', intval($request->mahalle))
            ->get();
        #dispatch city-district control
        $dispatchCityDistrict = DB::table('view_city_districts')
            ->where('city_id', intval($request->sevkIl))
            ->where('district_id', intval($request->sevkIlce))
            ->exists();
        if ($dispatchCityDistrict == null) {
            $request->flash();
            return back()->with('error', 'Lütfen geçerli bir il-ilçe seçinizi');
        }
        $dispatchCityDistrict = DB::table('view_city_districts')
            ->where('city_id', intval($request->sevkIl))
            ->where('district_id', intval($request->sevkIlce))
            ->get();

        ### => insert transaction
        $update = Currents::find($id)
            ->update([
                'name' => tr_strtoupper($request->adSoyadFirma),
                'tax_administration' => tr_strtoupper($request->vergiDairesi),
                'tckn' => $request->vknTckn,
                'city' => tr_strtoupper($cityDistrict[0]->city_name),
                'district' => tr_strtoupper($cityDistrict[0]->district_name),
                'neighborhood' => tr_strtoupper($cityDistrict[0]->neighborhood_name),
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
                'dispatch_city' => tr_strtoupper($dispatchCityDistrict[0]->city_name),
                'dispatch_district' => tr_strtoupper($dispatchCityDistrict[0]->district_name),
                'dispatch_post_code' => $request->sevkPostaKodu,
                'dispatch_adress' => tr_strtoupper($request->sevkAdres),
                'status' => '1',
                'agency' => $request->acente,
                'bank_iban' => $request->iban,
                'bank_owner_name' => tr_strtoupper($request->hesapSahibiTamIsim),
                'discount' => getDoubleValue($request->iskonto),
                'reference' => tr_strtoupper($request->referans),
                'created_by_user_id' => Auth::id(),
                'contract_start_date' => $request->sozlesmeBaslangicTarihi,
                'contract_end_date' => $request->sozlesmeBitisTarihi . ' 23:59:58',
                'mb_status' => $request->mbStatus,
            ]);

        if ($update) {

            $current = Currents::find($id);

            $update = CurrentPrices::where('current_code', $current->current_code)
                ->update([
                    'file_price' => getDoubleValue($request->dosyaUcreti),
                    'mi_price' => getDoubleValue($request->miUcreti),
                    'd_1_5' => getDoubleValue($request->d1_5),
                    'd_6_10' => getDoubleValue($request->d6_10),
                    'd_11_15' => getDoubleValue($request->d11_15),
                    'd_16_20' => getDoubleValue($request->d16_20),
                    'd_21_25' => getDoubleValue($request->d21_25),
                    'd_26_30' => getDoubleValue($request->d26_30),
                    'd_31_35' => getDoubleValue($request->d31_35),
                    'd_36_40' => getDoubleValue($request->d36_40),
                    'd_41_45' => getDoubleValue($request->d41_45),
                    'd_46_50' => getDoubleValue($request->d46_50),
                    'amount_of_increase' => getDoubleValue($request->ustuDesi),
                    'collect_price' => getDoubleValue($request->tahsilatEkHizmetBedeli),
                    'collect_amount_of_increase' => getDoubleValue($request->tahsilatEkHizmetBedeli200Ustu),
                ]);

            if ($update) {
                GeneralLog($current->current_code . " kodlu kusumsal cari Güncellendi.");
                return back()->with('success', 'Cari Güncellendi!');
            } else {
                $request->flash();
                return back()->with('success', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
            }

        }
    }


    public function destroy($id)
    {
        $destroy = Currents::find(intval($id))->delete();
        if ($destroy)
            return 1;

        return 0;
    }

    public function ajaxTransaction(Request $request, $transaction)
    {
        $jsonData = [];

        switch ($transaction) {
            case 'GetTaxOffices':

                $request->SearchTerm = tr_strtoupper(tr_strtolower(enCharacters(urlCharacters($request->SearchTerm))));
                $data = DB::table('tax_offices')
                    ->where('office', 'like', '%' . $request->SearchTerm . '%')
                    ->limit(50)
                    ->get(['id', 'office', 'city', 'district']);
                $jsonData = $data;
                break;

            case 'GetAgencies':

                $request->SearchTerm = tr_strtoupper(tr_strtolower(enCharacters(urlCharacters($request->SearchTerm))));
                $data = DB::table('agencies')
                    ->where('agency_name', 'like', '%' . $request->SearchTerm . '%')
                    ->whereRaw('deleted_at is null')
                    ->limit(50)
                    ->get(['id', 'agency_name', 'city', 'district']);

                $jsonData = $data;
                break;

            case 'GetCurrentInfo':
                $currentInfo = DB::table('currents')
                    ->join('agencies', 'currents.agency', '=', 'agencies.id')
                    ->join('view_users_all_info', 'currents.created_by_user_id', '=', 'view_users_all_info.id')
                    ->select(['currents.*', 'agencies.agency_name', 'agencies.city as agency_city', 'agencies.district as agency_district', 'agencies.agency_code', 'view_users_all_info.name_surname as creator_user_name', 'view_users_all_info.display_name as creator_display_name'])
                    ->where('currents.id', $request->currentID)
                    ->first();

                $price = CurrentPrices::where('current_code', $currentInfo->current_code)->first();

                $jsonData = ['current' => $currentInfo, 'price' => $price];


                break;

            case 'ChangeStatus':

                $rules = [
                    'status' => 'required|in:0,1',
                    'currentID' => 'required|numeric'
                ];
                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => '-1',
                        'errors' => $validator->getMessageBag()->toArray()
                    ], 200);
                }

                $update = Currents::find(intval($request->currentID))
                    ->update(['status' => $request->status]);

                if ($update) {

                    $statu = $request->status == '1' ? 'aktif' : 'pasif';
                    $current = Currents::find(intval($request->currentID));
                    $properties = [
                        'Eylemi gerçekleştiren' => Auth::user()->name_surname,
                        'id\'si' => Auth::id(),
                        'İşlem Yapılan Kullanıcı' => $current->name,
                        'Statü' => $statu
                    ];

                    $log = $current->name . " İsimli gönderici cari " . $statu . ' hale getirildi';
                    activity()
                        ->performedOn($current)
                        ->inLog('Current Enabled-Disabled')
                        ->withProperties($properties)
                        ->log($log);

                    return response()->json(['status' => 1], 200);
                } else
                    return response()->json([
                        'status' => 0,
                        'message' => "Bir hata oluştu, lütfen daha sonra tekrar deneyin!"
                    ], 200);
                break;

            case 'ConfirmCurrent':

                $current = Currents::find($request->currentID);

                $PermRoleIDs = collect(GetCurrentConfirmerRoleIDs());
                if ($current->confirmed == '1')
                    $jsonData = ['status' => -1, 'message' => 'Cari zaten onaylı!'];
                else if (!$PermRoleIDs->contains(Auth::user()->role_id))
                    $jsonData = ['status' => -1, 'message' => 'Geçersiz Yetki!'];
                else if ($PermRoleIDs->contains(Auth::user()->role_id)) {

                    $update = Currents::find($request->currentID)
                        ->update([
                            'confirmed' => '1',
                            'confirmed_by_user_id' => Auth::id()
                        ]);

                    activity()
                        ->inLog('Cari Onayı')
                        ->performedOn($current)
                        ->log($current->current_code . " kodlu cari hesap onaylandı!");

                    if ($update)
                        $jsonData = ['status' => 1];
                }


                break;

            default:
                return 'no-case';
                break;
        }

        return response()
            ->json($jsonData, 200);

    }

    public function getCurrents(Request $request)
    {
        $record = $request->record;
        $status = $request->status;
        $agency = $request->agency;
        $name = $request->name;
        $currentCode = str_replace([' ', '_'], '', $request->currentCode);
        $creatorUser = $request->creatorUser;
        $category = $request->category != -1 ? $request->category : '';
        $confirmed = $request->confirmed;

        $currents = DB::table('currents')
            ->join('agencies', 'currents.agency', '=', 'agencies.id')
            ->join('users', 'currents.created_by_user_id', '=', 'users.id')
            ->select(['currents.*', 'agencies.agency_name', 'users.name_surname'])
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($agency ? 'agency=' . $agency : '1 > 0')
            ->whereRaw($creatorUser ? 'created_by_user_id=' . $creatorUser : '1 > 0')
            ->whereRaw($status ? "currents.`status`='" . $status . "'" : '1 > 0')
            ->whereRaw($category ? "currents.`category`='" . $category . "'" : '1 > 0')
            ->whereRaw($request->filled('confirmed') ? "confirmed='" . $confirmed . "'" : '1 > 0')
            ->whereRaw($name ? "name like '%" . $name . "%'" : '1 > 0')
            ->whereRaw($record == '1' ? 'currents.deleted_at is null' : 'currents.deleted_at is not null')
            ->where('current_type', 'Gönderici');

        return datatables()->of($currents)
            ->editColumn('name', function ($current) {
                return Str::words($current->name, 3, '...');
            })
            ->editColumn('city', function ($current) {
                return $current->city . "/" . $current->district;
            })
            ->setRowId(function ($currents) {
                return "current-item-" . $currents->id;
            })
            ->editColumn('status', function ($currents) {
                return $currents->status == '1' ? 'Aktif' : 'Pasif';
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->rawColumns(['edit'])
            ->make(true);
    }

    public function customersIndex()
    {
        $data['users'] = DB::table('view_users_general_info')->get();
        $data['agencies'] = Agencies::all();
        $data['tc'] = TransshipmentCenters::all();
        $data['roles'] = Roles::all();
        $data['cities'] = Cities::all();

        GeneralLog('Müşteriler sayfası görüntülendi.');
        return view('backend.customers.agency.index', compact(['data']));
    }

    public function getAllCustomers(Request $request)
    {
        $category = $request->category;
        $city = $request->city;
        $currentCode = str_replace([' ', '_'], ['', ''], $request->currentCode);
        $customer_name_surname = $request->customer_name_surname;
        $customer_type = $request->customer_type;
        $phone = $request->phone;

        $data = DB::table('currents')
            ->select('currents.*', 'users.name_surname')
            ->join('users', 'users.id', '=', 'currents.created_by_user_id')
            ->join('agencies', 'agencies.id', '=', 'users.agency_code')
            ->whereRaw($category ? "category='" . $category . "'" : '1 > 0')
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($city ? "city='" . $city . "'" : '1 > 0')
            ->whereRaw($customer_name_surname ? "name='" . $customer_name_surname . "'" : '1 > 0')
            ->whereRaw($customer_type ? "current_type='" . $customer_type . "'" : '1 > 0')
            ->whereRaw($phone ? "gsm='" . $phone . "'" : '1 > 0')
            ->where('agency', Auth::user()->agency_code)
            ->whereRaw('currents.deleted_at is null');

        return datatables()->of($data)
            ->editColumn('current_code', function ($current) {
                return '<b class="customer-detail" id="'.$current->id.'" style="text-decoration:underline; color:#000; cursor:pointer; user-select:none">'.CurrentCodeDesign($current->current_code).'</b>';
            })
            ->editColumn('free', function ($current) {
                return '';
            })
            ->addColumn('edit', 'backend.customers.agency.columns.edit')
            ->rawColumns(['edit', 'current_code'])
            ->make(true);
    }


    public function getCustomerById(Request $request)
    {
        $id = $request->user;
        $data = DB::select("SELECT
       currents.authorized_name,
       currents.building_no,currents.category,currents.city,currents.contract_end_date,currents.contract_start_date,currents.created_by_user_id,
       currents.current_code,currents.current_type,currents.discount,currents.dispatch_adress,currents.dispatch_city,currents.dispatch_district,
        currents.dispatch_post_code,currents.district,currents.door_no,currents.email,currents.floor,currents.gsm,currents.gsm2,currents.id,
       currents.name,currents.neighborhood,currents.phone,currents.phone2,currents.reference,currents.status,currents.street,currents.street2,
       currents.tax_administration,currents.tckn,currents.vkn,currents.web_site,currents.created_at,
        agencies.city as agencies_city , agencies.district as agencies_district, agencies.agency_name FROM currents
            INNER JOIN users ON users.id = currents.created_by_user_id
            INNER JOIN agencies ON agencies.id = users.agency_code
             WHERE currents.id = $id");

        if ($data[0]->current_type == 'Gönderici') {
            $cargo = Cargoes::where('sender_id', $id)
                ->select(['sender_name', 'tracking_no', 'receiver_name', 'status', 'cargo_type', 'total_price'])
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();
        } else if ($data[0]->current_type == 'Alıcı')
            $cargo = Cargoes::where('receiver_id', $id)
                ->select(['sender_name', 'tracking_no', 'receiver_name', 'status', 'cargo_type', 'total_price'])
                ->orderBy('id', 'desc')
                ->limit(10)
                ->get();

        $data[0]->created_at = Carbon::parse($data[0]->created_at)->diffInSeconds(Carbon::now());

        return response()->json(['data' => $data, 'cargo' => $cargo]);
    }


    public function printCurrentContract($CurrentCode)
    {

        $CurrentCode = str_replace(' ', '', $CurrentCode);
        $templateProccessor = new TemplateProcessor('backend/word-template/CurrentContract.docx');

        $current = Currents::where('current_code', $CurrentCode)->first();
        $currentPrice = CurrentPrices::where('current_code', $CurrentCode)->first();
        $agency = Agencies::find($current->agency);

        $templateProccessor->setValue('date', date('d/m/Y'));
        $templateProccessor->setValue('name', $current->name);

        $templateProccessor->setValue('file', $currentPrice->file_price);
        $templateProccessor->setValue('mi', $currentPrice->mi_price);
        $templateProccessor->setValue('d1_5', $currentPrice->d_1_5);
        $templateProccessor->setValue('d6_10', $currentPrice->d_6_10);
        $templateProccessor->setValue('d11_15', $currentPrice->d_11_15);
        $templateProccessor->setValue('d16_20', $currentPrice->d_16_20);
        $templateProccessor->setValue('d21_25', $currentPrice->d_21_25);
        $templateProccessor->setValue('d26_30', $currentPrice->d_26_30);
        $templateProccessor->setValue('d31_35', $currentPrice->d_31_35);
        $templateProccessor->setValue('d36_40', $currentPrice->d_36_40);
        $templateProccessor->setValue('d41_45', $currentPrice->d_41_45);
        $templateProccessor->setValue('d46_50', $currentPrice->d_46_50);
        $templateProccessor->setValue('amount_of_increase', $currentPrice->amount_of_increase);
        $templateProccessor->setValue('CurrentCode', CurrentCodeDesign($current->current_code));
        $templateProccessor->setValue('category', $current->category);
        $templateProccessor->setValue('tax_office', $current->tax_administration);
        $templateProccessor->setValue('vkn', $current->tckn);
        $templateProccessor->setValue('agency', $agency->city . '/' . $agency->district . " - " . $agency->agency_name . " (" . $agency->agency_code . ")");
        $templateProccessor->setValue('contract_start_date', Carbon::parse($current->contract_start_date)->format('d/m/Y'));
        $templateProccessor->setValue('contract_end_date', Carbon::parse($current->contract_end_date)->format('d/m/Y'));
        $templateProccessor->setValue('contract_lifetime', Carbon::parse($current->contract_start_date)->diffInDays($current->contract_end_date));

        $fileName = 'CS-' . substr($current->name, 0, 30) . '.docx';
        $pdfName = 'CS-' . substr($current->name, 0, 30) . '.pdf';


        $templateProccessor
            ->saveAs($fileName);

        return response()
            ->download($fileName)
            ->deleteFileAfterSend(true);

//        /* Set the PDF Engine Renderer Path */
//        $domPdfPath = base_path('vendor/dompdf/dompdf');
//        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
//        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
//
//        $content = IOFactory::load($fileName);
//
//        $PdfWriter = IOFactory::createWriter($content, 'PDF');
//
//        $PdfWriter->save($pdfName);
//
//        return response()
//            ->download($pdfName)
//            ->deleteFileAfterSend(true);


    }

    public function deleteCustomer($id)
    {
        $current = Currents::find($id);
        $creatorUser = User::find($current->created_by_user_id);

        if (Auth::user()->agency_code != $creatorUser->agency_code)
            return response()
                ->json(['status' => 0, 'message' => 'Şubenize ait bir müşteri olmadığından bu müşteriyi silemezsiniz!'],403);
        elseif( Carbon::parse($current->created_at)->diffInSeconds(Carbon::now()) < 86400 ){

            $current->delete();

            GeneralLog( $current->current_code.' Cari Kodlu müşteri silindi!');


            return response()->json(['status'=> 1 ,'message'=> 'Bşarılı Silindi!'],200);
        }
        elseif(Carbon::parse($current->created_at)->diffInSeconds(Carbon::now()) > 86400 ){
            return response()->json(['status' => 0, 'message'=> '24 saat geçtigi için silemezsiniz!'], 403);
        }

    }

}
