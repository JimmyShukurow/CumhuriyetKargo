<?php

namespace App\Http\Controllers\Customer;

use App\Actions\CKGSis\Customer\AjaxTransaction\ConfirmCurrentWithVKNAction;
use App\Actions\CKGSis\Customer\AjaxTransaction\CreateAgencyContractedCustomerAction;
use App\Actions\CKGSis\Customer\AjaxTransaction\TaxOfficesAction;
use App\Actions\CKGSis\Customer\DeleteCustomerAction;
use App\Actions\CKGSis\Customer\GetAllCustomersAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\GetTaxOfficesAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\CurrentPrices;
use App\Models\Currents;
use App\Models\PriceDrafts;
use App\Models\Roles;
use App\Models\TransshipmentCenterDistricts;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
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
        return GetAllCustomersAction::run($request);
    }

    public function getCustomerById(Request $request)
    {
        $currentInfo = DB::table('currents')
            ->join('agencies', 'currents.agency', '=', 'agencies.id')
            ->join('view_users_all_info', 'currents.created_by_user_id', '=', 'view_users_all_info.id')
            ->select(['currents.*', 'agencies.agency_name', 'agencies.city as agency_city', 'agencies.district as agency_district', 'agencies.agency_code', 'view_users_all_info.name_surname as creator_user_name', 'view_users_all_info.display_name as creator_display_name'])
            ->where('currents.id', $request->user)
            ->first();


        $currentInfo->created_at_time = Carbon::parse($currentInfo->created_at)->diffInSeconds(Carbon::now());


        $price = CurrentPrices::where('current_code', $currentInfo->current_code)->first();

        return $jsonData = ['current' => $currentInfo, 'price' => $price];

    }

    public function deleteCustomer($id)
    {
        return DeleteCustomerAction::run($id);
    }

    public function create($type)
    {
        switch ($type) {
            case 'Receiver':
                $data['cities'] = Cities::all();
                GeneralLog('Yeni alıcı oluştur sayfası görüntülendi.');
                return view('backend.customers.agency.create.receiver', compact(['data']));
                break;

            case 'Sender':
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

                $data['cities'] = Cities::all();
                GeneralLog('Yeni gönderici oluştur sayfası görüntülendi.');
                return view('backend.customers.agency.create.sender', compact(['data']));
                break;


            case 'Contracted':
                $data['cities'] = Cities::all();
                $data['price_drafts'] = PriceDrafts::where('agency_permission', '1')->get();
                $data['user_branch'] = getUserBranchInfo();
                return view('backend.customers.agency.create.contracted', compact('data'));
                break;

            default:
                return redirect(route('customers.index'))
                    ->with('error', 'Geçersiz müşteri tipi!');
                break;
        }
    }

    public function ajaxTransaction(Request $request, $val)
    {
        switch ($val) {
            case 'TaxOffices':
                return TaxOfficesAction::run($request);
                break;

            case 'ConfirmCurrentWithVKN':
                return ConfirmCurrentWithVKNAction::run($request);
                break;

            case 'CreateAgencyContractedCustomer':
                return CreateAgencyContractedCustomerAction::run($request);
                break;

            default:
                return response()
                    ->json(['status' => '0', 'message' => 'no-case'], 200);
                break;
        }
    }

}
