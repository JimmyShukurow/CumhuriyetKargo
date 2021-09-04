<?php

namespace App\Http\Controllers\Backend\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cities;
use App\Models\Districts;
use App\Models\Neighborhoods;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DataTables\AgenciesDataTable;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class AgencyController extends Controller
{
    public function index(AgenciesDataTable $dataTable)
    {
        $data['agencies'] = Agencies::all();
        GeneralLog('Acenteler sayfası görüntülendi.');
        return view('backend.agencies.index', compact('data'));
    }

    public function getAgencies()
    {
//        $agencies = Agencies::orderBy('created_at', 'desc')->get();
        $agencies = DB::select('CALL proc_agency_region()');

        return DataTables::of($agencies)
            ->setRowClass(function ($agency) {
                return 'agency-item-' . $agency->id;
            })
            ->setRowId(function ($agency) {
                return 'agency-item-' . $agency->id;
            })
//            ->addColumn('intro', 'Hi {{$name_surname}}')
            ->addColumn('regional_directorates', function ($agency) {
                return $agency->regional_directorates != '' ? "$agency->regional_directorates  B.M." : "";
            })
            ->addColumn('tc_name', function ($agency) {
                return $agency->tc_name != '' ? "$agency->tc_name  T.M." : "";
            })
            ->addColumn('edit', 'backend.agencies.column')
            ->rawColumns(['edit'])
            ->editColumn('city', function ($agency) {
                return $agency->city . '/' . $agency->district;
            })
            ->editColumn('created_at', function ($agency) {
                return Carbon::parse($agency->created_at)->format('Y-m-d H:i:s');
            })
            ->make(true);
    }

    public function addAgency()
    {
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        GeneralLog('Acente oluştur sayfası görüntülendi.');
        return view('backend.agencies.create', compact(['data']));
    }

    public function insertAgency(Request $request)
    {
        $request->validate([
            'name_surname' => 'required',
            'phone' => 'required',
            'city' => 'required|numeric',
            'district' => 'required|numeric',
            'neighborhood' => 'required|numeric',
            'agency_name' => 'required',
            'transshipment_center' => 'nullable|numeric',
            'agency_development_officer' => 'required',
            'adress' => 'required'
        ]);

        $phone = CharacterCleaner($request->phone);
        $phone2 = CharacterCleaner($request->phone2);

        $city = Cities::find($request->city);
        $district = Districts::find($request->district);
        $neighborhood = Neighborhoods::find($request->neighborhood);
        $post_code = '0' . $neighborhood->post_code;

//        echo $city->city_name . ' => ' . $district->district_name . ' => ' . $neighborhood->neighborhood_name . ' => ' . $post_code;

        $there_is_some_agency_code = DB::table('agencies')
            ->where('agency_code', $post_code)
            ->first();

        if ($there_is_some_agency_code !== null) {

            $post_code = substr($post_code, 1, 5);
            $counter = 1;

            while ($there_is_some_agency_code !== null) {
                if (strlen($post_code) > 5) $post_code = substr($post_code, 0, 5);
                $post_code = $post_code . $counter;
                $there_is_some_agency_code = DB::table('agencies')->where('agency_code', $post_code)->first();
                $counter++;
            }
        }

        //  echo '<br>' . $post_code;

        $insert = Agencies::create([
            'name_surname' => tr_strtoupper($request->name_surname),
            'phone' => $request->phone,
            'phone2' => $request->phone2,
            'transshipment_center_code' => $request->transshipment_center,
            'agency_code' => $post_code,
            'city' => tr_strtoupper($city->city_name),
            'district' => tr_strtoupper($district->district_name),
            'neighborhood' => tr_strtoupper($neighborhood->neighborhood_name),
            'agency_name' => tr_strtoupper($request->agency_name),
            'agency_development_officer' => tr_strtoupper($request->agency_development_officer),
            'adress' => tr_strtoupper($request->adress)
        ]);

        if ($insert) return back()->with('success', 'Acente Eklendi!');

        $request->flash();
        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin.');
    }

    public function agencyInfo(Request $request)
    {
        $agency_id = $request->agency_id;

        $data['agency'] = DB::select('CALL proc_agency_region_search(' . $agency_id . ')');
        $data['employees'] = DB::table('view_user_role')
            ->where('agency_code', $agency_id)
            ->orderBy('display_name', 'asc')
            ->get();

        return response()->json($data, 200);
    }

    public function destroyAgency(Request $request)
    {
        $destroy = Agencies::find(intval($request->destroy_id))->delete();
        if ($destroy) {
            return 1;
        }
        return 0;
    }

    public function editAgency($id)
    {
        $agency = Agencies::where('id', $id)->first();

        if ($agency === null) return redirect(route('agency.Index'))->with('error', "Düzenlemek istediğiniz acente bulunamadı!");

        $data['districts'] = DB::table('view_city_district_neighborhoods')
            ->select('district_id', 'district_name')
            ->where('city_name', $agency->city)
            ->distinct()
            ->get();

        $data['neighborhoods'] = DB::table('view_city_district_neighborhoods')
            ->where('city_name', $agency->city)
            ->where('district_name', $agency->district)
            ->get();

        $data['transshipment_centers'] = TransshipmentCenters::all();

        $data['cities'] = Cities::all();
        GeneralLog($id . ' id\'li Acente Düzenle sayfası görüntülendi.');
        return view('backend.agencies.edit', compact(['data', 'agency']));
    }

    public function updateAgency(Request $request, $id)
    {
        $request->validate([
            'name_surname' => 'required',
            'phone' => 'required',
            'city' => 'required|numeric',
            'district' => 'required|numeric',
            'neighborhood' => 'required|numeric',
            'agency_name' => 'required',
            'transshipment_center' => 'nullable|numeric',
            'agency_development_officer' => 'required',
            'adress' => 'required'
        ]);


        $cityDistrictNeighborhood = DB::table('view_city_district_neighborhoods')
            ->where('city_id', $request->city)
            ->where('district_id', $request->district)
            ->where('neighborhood_id', $request->neighborhood)
            ->get();

        if ($cityDistrictNeighborhood == null) {
            $request->flash();
            return back()
                ->with('error', 'Lütfen geçerli bir il ilçe ve mahalle seçin!');
        }

        $post_code = '0' . $cityDistrictNeighborhood[0]->post_code;
//        echo $city->city_name . ' => ' . $district->district_name . ' => ' . $neighborhood->neighborhood_name . ' => ' . $post_code;

        $there_is_some_agency_code = DB::table('agencies')
            ->where('agency_code', $post_code)
            ->where('agency_code', '<>', $post_code)
            ->first();

        if ($there_is_some_agency_code !== null) {

            $post_code = substr($post_code, 1, 5);
            $counter = 1;

            while ($there_is_some_agency_code !== null) {
                if (strlen($post_code) > 5) $post_code = substr($post_code, 0, 5);
                $post_code = $post_code . $counter;
                $there_is_some_agency_code = DB::table('agencies')->where('agency_code', $post_code)->first();
                $counter++;
            }
        }

        //  echo '<br>' . $post_code;

        $insert = Agencies::find($id)
            ->update([
                'name_surname' => tr_strtoupper($request->name_surname),
                'phone' => $request->phone,
                'phone2' => $request->phone2,
                'transshipment_center_code' => $request->transshipment_center,
                'agency_code' => $post_code,
                'city' => tr_strtoupper($cityDistrictNeighborhood[0]->city_name),
                'district' => tr_strtoupper($cityDistrictNeighborhood[0]->district_name),
                'neighborhood' => tr_strtoupper($cityDistrictNeighborhood[0]->neighborhood_name),
                'agency_name' => tr_strtoupper($request->agency_name),
                'agency_development_officer' => tr_strtoupper($request->agency_development_officer),
                'adress' => tr_strtoupper($request->adress)
            ]);

        if ($insert) return back()->with('success', 'Acente Güncellendi!');

        $request->flash();
        return back()->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin.');
    }


}
