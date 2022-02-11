<?php

namespace App\Http\Controllers\Backend\Region;

use App\Actions\CKGSis\MainCargo\GetMainCargoesAction;
use App\Actions\CKGSis\Region\GetRegionCargoesAction;
use App\Actions\CKGSis\Region\GetRegionMainDailySummeryAction;
use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{

    public function situationIndex()
    {
        $data['tc'] = TransshipmentCenters::find(Auth::user()->tc_code);

        $data['agencies'] = Agencies::all();
        $data['gm_users'] = DB::table('users')
            ->where('agency_code', 1)
            ->get();

        ## get agency district
        $agency = Agencies::find(1);
        $data['districts'] = DB::table('view_city_districts')
            ->where('city_name', $agency->city)
            ->get();

        $data['tc'] = TransshipmentCenters::find(Auth::user()->tc_code);
        $regionAgencies = DB::table('view_agency_region')
            ->select('id')
            ->where('tc_id', $data['tc']->id)
            ->get();

        $regionAgencies = $regionAgencies->pluck('id');

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
        $data['cities'] = Cities::all();

        ## daily report start
        $daily['package_count'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->where('cargo_type', '<>', 'Dosya-Mi')
            ->whereIn('departure_agency_code', $regionAgencies)
            ->count();

        $daily['file_count'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->whereIn('cargo_type', ['Dosya', 'Mi'])
            ->whereIn('departure_agency_code', $regionAgencies)
            ->count();

        $daily['total_cargo_count'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->whereIn('departure_agency_code', $regionAgencies)
            ->count();

        $daily['total_desi'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->whereIn('departure_agency_code', $regionAgencies)
            ->sum('desi');

        $daily['total_number_of_pieces'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->whereIn('departure_agency_code', $regionAgencies)
            ->whereNotIn('cargo_type', ['Dosya', 'Mi'])
            ->sum('number_of_pieces');

        $daily['total_endorsement'] = DB::table('cargoes')
            ->whereRaw("created_at BETWEEN '" . date('Y-m-d') . " 00:00:00' and '" . date('Y-m-d') . " 23:59:59'")
            ->whereRaw('deleted_at is null')
            ->whereIn('departure_agency_code', $regionAgencies)
            ->sum('total_price');

        $daily['total_endorsement'] = round($daily['total_endorsement'], 2);
        ## daily report end

        $daily['total_desi'] = round($daily['total_desi'], 2);

        GeneralLog('Kargolar Ana Menü görüntülendi.');
        return view('backend.region.region_situation', compact(['data', 'daily']));
    }

    public function relationPlaces()
    {
        $data['cities'] = DB::table('transshipment_center_districts')
            ->orderBy('city')
            ->where('tc_id', Auth::user()->tc_code)
            ->get();

        $data['agencies'] = DB::table('view_agency_region')
            ->where('tc_id', Auth::user()->tc_code)
            ->orderBy('city')
            ->get();

        $data['tc'] = TransshipmentCenters::find(Auth::user()->tc_code);

        GeneralLog('Bölgeye bağlı yerler sayfası görüntülendi.');
        return view('backend.region.relation_places', compact('data'));
    }

    public function ajaxTransactions(Request $request, $val)
    {

        switch ($val) {
            case 'GetRegionMainDailySummeryAction':
                return GetRegionMainDailySummeryAction::run($request);
                break;

            case'GetRegionMainCargoes':
                return GetRegionCargoesAction::run($request);
                break;


            default:
                break;
        }

    }
}
