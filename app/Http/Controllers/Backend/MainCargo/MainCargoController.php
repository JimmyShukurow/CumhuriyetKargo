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
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetGlobalCargoesGmAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetMainDailySummeryAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetMultipleCargoInfoAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetPriceForCustomersAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\GetReceiversAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\MakeCargoCancellationApplicationAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\SaveCurrentAction;
use App\Actions\CKGSis\MainCargo\AjaxTransactions\SaveReceiverAction;
use App\Actions\CKGSis\MainCargo\GetCancelledCargoesAction;
use App\Actions\CKGSis\MainCargo\GetGlobalCargoesAction;
use App\Actions\CKGSis\MainCargo\GetMainCargoesAction;
use App\Actions\CKGSis\MainCargo\StatementOfResponsibilityAction;
use App\Http\Controllers\Controller;
use App\Models\AdditionalServices;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\Currents;
use App\Models\FilePrice;
use App\Models\Settings;
use App\Models\TicketDetails;
use App\Models\Tickets;
use App\Models\TransshipmentCenterDistricts;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\TemplateProcessor;

class MainCargoController extends Controller
{
    public function searchCargo()
    {
        $data['cities'] = Cities::all();

        GeneralLog('Kargo sorgulama sayfas?? g??r??nt??lendi.');
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

        $daily = GetMainDailySummeryAction::run();

        $daily['total_desi'] = round($daily['total_desi'], 2);

        $cryptedData = Crypt::encryptString(Auth::id());

        GeneralLog('Kargolar Ana Men?? g??r??nt??lendi.');
        return view('backend.main_cargo.main.index.index', compact(['data', 'daily', 'cryptedData']));
    }

    public function newCargo()
    {
        $data['additional_service'] = AdditionalServices::orderBy('order')->get();
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

        # evrensel posta hizmetleri ??creti
        $postServicePercent = DB::table('settings')
            ->where('key', 'post_services_percent')
            ->first();
        $postServicePercent = $postServicePercent->value;

        $fee['postal_services_fee'] = round(($fee['first_file_price'] * $postServicePercent) / 100, 2);

        $totalFirst = 0;
        $totalFirstNoKDV = 0;
        $totalFirst += $fee['first_total'] + $fee['first_file_price'] + $fee['postal_services_fee'];
        $totalFirstNoKDV = round($fee['first_total'] + $fee['first_file_price'] + $fee['postal_services_fee'], 2);

        $fee['first_total'] = round($totalFirst + ((18 * $totalFirst) / 100), 2);
        $fee['first_total_no_kdv'] = $totalFirstNoKDV;

        $data['collectible_cargo'] = Settings::where('key', 'collectible_cargo')->first();

        GeneralLog('Kargo olu??tur sayfas?? g??r??nt??lendi.');
        return view('backend.main_cargo.main.create.create', compact(['data', 'fee', 'agency', 'tc']));
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
                return response()
                    ->json(GetMainDailySummeryAction::run(), 200);
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
        return GetMainCargoesAction::run($request);
    }

    public function getGlobalCargoes(Request $request)
    {
        return GetGlobalCargoesAction::run($request);
    }

    public function statementOfResponsibility($ctn)
    {
        return StatementOfResponsibilityAction::run($ctn);
    }

    public function cancelledCargoesIndex()
    {
        $data['cities'] = Cities::all();

        GeneralLog('??ptal edilen kargolar sayfas?? g??r??nt??lendi.');
        return view('backend.main_cargo.cancelled_cargoes.index', compact(['data']));
    }

    public function getCancelledCargoes(Request $request)
    {
        return GetCancelledCargoesAction::run($request);
    }

    public function searchCargoGM()
    {
        $data['cities'] = Cities::all();

        $data['agencies'] = DB::table('agencies')
            ->orderBy('agency_name')
            ->whereRaw('deleted_at is null')
            ->get();
        $data['tc'] = DB::table('transshipment_centers')
            ->orderBy('tc_name')
            ->get();


        GeneralLog('GM Kargo sorgulama sayfas?? g??r??nt??lendi.');
        return view('backend.main_cargo.search_cargo.gm', compact(['data']));
    }

    public function getGlobalCargoesGM(Request $request)
    {
        return GetGlobalCargoesGmAction::run($request);
    }

    public function calculateServiceFee()
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
        $fee['first_file_price'] = 0;

        # evrensel posta hizmetleri ??creti
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

        GeneralLog('??cret Hesapla sayfas?? g??r??nt??lendi.');

        return view('backend.main_cargo.main.calculate_service_fee', compact(['data', 'fee', 'agency', 'tc']));
    }
}
