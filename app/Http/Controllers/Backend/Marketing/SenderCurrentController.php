<?php

namespace App\Http\Controllers\Backend\Marketing;

use App\Actions\CKGSis\Marketing\PriceDrafts\GetPriceDraftsAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\ChangeStatusAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\ConfirmCurrentAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\GetAgenciesAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\GetCurrentInfoAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\GetTaxOfficesAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\GetCurrentsAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\PrintCurrentContractAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketing\SenderCurrentRequest;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Models\PriceDrafts;
use App\Models\Roles;
use App\Models\TransshipmentCenters;
use App\Models\User;
use App\Notifications\GeneralNotify;
use App\Rules\AgencyControl;
use App\Rules\CityDistrictNeighborhoodRule;
use App\Rules\CityDistrictRule;
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

        return view('backend.marketing.sender_currents.index.index', compact('data'));
    }


    public function create()
    {
        $data['cities'] = Cities::all();
        $data['price_drafts'] = PriceDrafts::all();
        $data['agencies'] = Agencies::orderBy('agency_name')->get();
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
            'ustuDesi' => ['required', new PriceControl],
            'mbStatus' => 'required|in:1,0',
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
                'agency' => $request->acente,
                'bank_iban' => $request->iban,
                'bank_owner_name' => tr_strtoupper($request->hesapSahibiTamIsim),
                'discount' => getDoubleValue($request->iskonto),
                'reference' => tr_strtoupper($request->referans),
                'confirmed' => '0',
                'created_by_user_id' => Auth::id(),
                'contract_start_date' => $request->sozlesmeBaslangicTarihi,
                'contract_end_date' => $request->sozlesmeBitisTarihi,
                'mb_status' => $request->mbStatus,
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
                'collect_price' => getDoubleValue($request->tahsilatEkHizmetBedeli),
                'collect_amount_of_increase' => getDoubleValue($request->tahsilatEkHizmetBedeli200Ustu),

            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $request->flash();
            return back()
                ->with('error', 'Cari fiyat kayıt işlemi esnasında bir hata oluştu!');
        }

        DB::commit();
        GeneralLog($current_code . " kodlu kusumsal cari oluşturuldu.");
        return back()->with('success', 'Cari oluşturuldu, Onay Bekliyor!');
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

        $data['price_drafts'] = PriceDrafts::all();

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
            'ustuDesi' => ['required', new PriceControl],
            'mbStatus' => 'required|in:1,0',
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

        $date = \Carbon\Carbon::createFromDate(date('Y'));
        $endOfYear = \Carbon\Carbon::parse(date('Y-m-d'))->endOfYear();

        $current = Currents::find($id);
        if ($current == null)
            return back()
                ->with('error', 'Müşteri bulunamadı!');

        $neighborhood = Neighborhoods::with('district.city')->where('id', $request->mahalle)->first();
        $dispatchDistrict = Districts::with('city')->where('id', $request->sevkIlce)->first();

        DB::beginTransaction();
        try {
            ### => insert transaction
            $insert = Currents::find($id)
                ->update([
                    'current_type' => 'Gönderici',
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
                    'agency' => $request->acente,
                    'bank_iban' => $request->iban,
                    'bank_owner_name' => tr_strtoupper($request->hesapSahibiTamIsim),
                    'discount' => getDoubleValue($request->iskonto),
                    'reference' => tr_strtoupper($request->referans),
                    'contract_start_date' => $request->sozlesmeBaslangicTarihi,
                    'contract_end_date' => $request->sozlesmeBitisTarihi,
                    'mb_status' => $request->mbStatus,
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $request->flash();
            return back()
                ->with('error', 'Cari güncelleme işlemi esnasında bir hata oluştu!');
        }

        try {
            $create = CurrentPrices::where('current_code', $current->current_code)
                ->update([
                    'price_draft_id' => $request->priceDraft,
                    'file' => getDoubleValue($request->dosyaUcreti),
                    'mi' => getDoubleValue($request->miUcreti),
                    'd_1_5' => getDoubleValue($request->d1_5),
                    'd_6_10' => getDoubleValue($request->d6_10),
                    'd_11_15' => getDoubleValue($request->d11_15),
                    'd_16_20' => getDoubleValue($request->d16_20),
                    'd_21_25' => getDoubleValue($request->d21_25),
                    'd_26_30' => getDoubleValue($request->d26_30),
                    'amount_of_increase' => getDoubleValue($request->ustuDesi),
                    'collect_price' => getDoubleValue($request->tahsilatEkHizmetBedeli),
                    'collect_amount_of_increase' => getDoubleValue($request->tahsilatEkHizmetBedeli200Ustu),

                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $request->flash();
            return back()
                ->with('error', 'Cari fiyat kayıt işlemi esnasında bir hata oluştu!');
        }

        DB::commit();
        GeneralLog($current_code . " kodlu kusumsal cari güncellendi.");
        return back()->with('success', 'Cari güncellendi!');
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
                $jsonData = GetTaxOfficesAction::run($request);
                break;

            case 'GetAgencies':
                $jsonData = GetAgenciesAction::run($request);
                break;

            case 'GetCurrentInfo':
                $jsonData = GetCurrentInfoAction::run($request);
                break;

            case 'ChangeStatus':
                return ChangeStatusAction::run($request);
                break;

            case 'ConfirmCurrent':
                $jsonData = ConfirmCurrentAction::run($request);
                break;

            case 'GetPriceDraft':
                return GetPriceDraftsAction::run($request);
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
        return GetCurrentsAction::run($request);
    }

    public function printCurrentContract($CurrentCode)
    {
        return PrintCurrentContractAction::run($CurrentCode);
    }

}
