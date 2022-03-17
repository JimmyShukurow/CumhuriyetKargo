<?php

namespace App\Http\Controllers\Customer;

use App\Actions\CKGSis\Customer\AjaxTransaction\ConfirmCurrentWithVKNAction;
use App\Actions\CKGSis\Customer\AjaxTransaction\TaxOfficesAction;
use App\Actions\CKGSis\Customer\DeleteCustomerAction;
use App\Actions\CKGSis\Customer\GetAllCustomersAction;
use App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction\GetTaxOfficesAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
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
                ->select(['sender_name', 'invoice_number', 'receiver_name', 'status', 'cargo_type', 'total_price'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else if ($data[0]->current_type == 'Alıcı')
            $cargo = Cargoes::where('receiver_id', $id)
                ->select(['sender_name', 'invoice_number', 'receiver_name', 'status', 'cargo_type', 'total_price'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

        $data[0]->current_code = CurrentCodeDesign($data[0]->current_code);


        $data[0]->created_at = Carbon::parse($data[0]->created_at)->diffInSeconds(Carbon::now());

        return response()->json(['data' => $data, 'cargo' => $cargo]);
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


            default:
                return response()
                    ->json(['status' => '0', 'message' => 'no-case'], 200);
                break;
        }
    }

}
