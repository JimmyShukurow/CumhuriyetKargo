<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Actions\CKGSis\MainCargo\AjaxTransactions\CalcCollectionPercentAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\CalcDesiPriceAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\ConfirmTCAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\CreateCargoAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\DistributionControlAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetAllCargoInfoAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetCancelledCargoInfoAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetCargoInfoAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetCargoMovementDetailsAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetCurrentsAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetCustomerAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetCustomersAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetDistanceAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetFilePriceAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetMainDailySummeryAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetMultipleCargoInfoAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetPriceForCustomersAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetReceiversAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\MakeCargoCancellationApplicationAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\SaveCurrentAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\SaveReceiverAction;
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
                    return SaveCurrentAction::run($request);
                break;

            case 'SaveReceiver':
                    return SaveReceiverAction::run($request);
                break;

            case 'ConfirmTC':
                    return ConfirmTCAction::run($request);
                break;

            case 'GetCustomer':
                    return GetCustomerAction::run($request);
                break;

            case 'GetCustomers':
                    return GetCustomersAction::run($request);
                break;

            case 'GetCurrents':
                    return GetCurrentsAction::run($request);
                break;

            case 'GetReceivers':
                    return GetReceiversAction::run($request);
                break;

            case 'GetDistance':
                    return GetDistanceAction::run($request);
                break;

            case 'CalcDesiPrice':
                    return CalcDesiPriceAction::run($request);
                break;

            case 'GetFilePrice':
                    return GetFilePriceAction::run($request);
                break;

            case 'GetPriceForCustomers':
                    return GetPriceForCustomersAction::run($request);
                break;

            case 'CreateCargo':
                    return CreateCargoAction::run($request);
                break;

            case 'CalcCollectionPercent':
                    return CalcCollectionPercentAction::run($request);
                break;

            case 'DistributionControl':
                    return DistributionControlAction::run($request);                
                break;

            # INDEX TRANSACTION START
            case 'GetCargoInfo':
                    return GetCargoInfoAction::run($request);
                break;

            case 'GetMultipleCargoInfo':
                    return GetMultipleCargoInfoAction::run($request);
                break;

            case 'GetCargoMovementDetails':
                    return GetCargoMovementDetailsAction::run($request);

            case 'GetMainDailySummery':
                    return GetMainDailySummeryAction::run($request);                
                break;

            case 'MakeCargoCancellationApplication':
                    return MakeCargoCancellationApplicationAction::run($request);
                break;
            # INDEX TRANSACTION END
            case 'GetAllCargoInfo':
                    return GetAllCargoInfoAction::run($request);
                break;

            case 'GetCancelledCargoInfo':
                    return GetCancelledCargoInfoAction::run($request);
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
