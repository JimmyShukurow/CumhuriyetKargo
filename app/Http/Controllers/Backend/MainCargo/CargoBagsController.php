<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\CargoBagDetails;
use App\Models\CargoBags;
use App\Models\Cities;
use App\Models\Roles;
use App\Models\TransshipmentCenters;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CargoBagsController extends Controller
{
    public function agencyIndex()
    {
        $data['users'] = DB::table('view_users_general_info')->get();
        $data['agencies'] = Agencies::all();
        $data['tc'] = TransshipmentCenters::all();
        $data['roles'] = Roles::all();
        $data['cities'] = Cities::all();


        $agency = DB::table('view_agency_region')
            ->where('id', Auth::user()->agency_code)
            ->first();

        $tc = TransshipmentCenters::find($agency->tc_id);

        $departure_point = $agency->agency_name;
        $arrival_point = $tc->tc_name . ' TRM.';
        $arrivalPoint = $arrival_point;
        $departurePoint = $departure_point;

        GeneralLog('Acente Torba & Çuval Sayfası görüntülendi.');
        return view('backend.main_cargo.cargo_bags.index', compact(['data', 'arrivalPoint', 'departurePoint']));
    }

    public function getCargoBags(Request $request)
    {
        $creator = $request->creatorUser;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $data = DB::table('cargo_bags')
            ->selectRaw('cargo_bags.*, (select count(*) from cargo_bag_details where bag_id = cargo_bags.id)  as included_cargo_count, users.name_surname')
            ->join('users', 'cargo_bags.creator_user_id', '=', 'users.id')
            ->whereRaw($creator ? "users.name_surname like '%" . $creator . "%'" : ' 1 > 0 ')
            ->whereRaw("cargo_bags.created_at between '" . $startDate . " 00:00:00" . "' and '" . $endDate . " 23:59:59" . "'");

        return datatables()->of($data)
            ->setRowId(function ($key) {
                return 'cargo_bag-item-' . $key->id;
            })
            ->editColumn('tracking_no', function ($key) {
                return '<b>' . TrackingNumberDesign($key->tracking_no) . '</b>';
            })
            ->editColumn('status', function ($key) {
                return $key->status == '1' ? '<b class="text-primary">Açık</b>' : '<b class="text-dark">Kapalı</b>';
            })
            ->addColumn('edit', 'backend.main_cargo.cargo_bags.columns.edit')
            ->rawColumns(['edit', 'tracking_no', 'status'])
            ->make(true);
    }

    public function createBag(Request $request)
    {
        if ($request->bag_type == '')
            return response()
                ->json(['status' => 0, 'message' => 'Tip alanı zorunludur.'], 200);

        if ($request->bag_type != 'Çuval' && $request->bag_type != 'Torba')
            return response()
                ->json(['status' => 0, 'message' => 'Lütfen geçerli bir tip seçiniz.'], 200);

        $createBag = CargoBags::create([
            'type' => $request->bag_type,
            'tracking_no' => CreateCargoBagTrackingNo(),
            'creator_user_id' => Auth::id(),
            'status' => '0',
        ]);

        if ($createBag)
            return response()
                ->json(['status' => 1], 200);
        else
            return response()
                ->json(['status' => 0, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz.'], 200);

    }

    public function getBagInfo(Request $request)
    {
        $bag_id = $request->bag_id;

        if ($bag_id == '')
            return response()
                ->json(['status' => 0, 'message' => 'Tip alanı zorunludur.'], 200);

        $bag = CargoBags::find($bag_id);

        if ($bag == null)
            return response()
                ->json(['status' => 0, 'message' => 'Bulunamadı!'], 200);

        $bag_details = DB::table('cargo_bag_details')
            ->select(['cargo_bag_details.*', 'cargoes.invoice_number', 'receiver_name', 'sender_name', 'arrival_city', 'arrival_district', 'cargo_type', 'users.name_surname'])
            ->where('cargo_bag_details.bag_id', $bag_id)
            ->join('cargoes', 'cargoes.id', '=', 'cargo_bag_details.cargo_id')
            ->join('users', 'users.id', '=', 'cargo_bag_details.loader_user_id')
            ->get();

        $data = [];

        foreach ($bag_details as $key) {
            $data[] = [
                'arrival_city' => $key->arrival_city,
                'arrival_district' => $key->arrival_district,
                'bag_id' => $key->bag_id,
                'cargo_id' => $key->cargo_id,
                'cargo_type' => $key->cargo_type,
                'created_at' => $key->created_at,
                'id' => $key->id,
                'invoice_number' => $key->invoice_number,
                'is_inside' => $key->is_inside,
                'loader_user_id' => $key->loader_user_id,
                'part_no' => $key->part_no,
                'receiver_name' => getNameFirstLatter($key->receiver_name),
                'sender_name' => getNameFirstLatter($key->sender_name),
                'unloaded_time' => $key->unloaded_time,
                'unloader_user_id' => $key->unloader_user_id,
                'name_surname' => $key->name_surname,
                'updated_at' => $key->updated_at
            ];
        }

        return response()
            ->json([
                'status' => 1,
                'bag' => $bag,
                'bag_details' => $data
            ], 200);

    }

    public function deleteCargoBag(Request $request)
    {
        $cargoBag = CargoBags::find($request->destroy_id);

        if ($cargoBag == null)
            return 0;

        $cargoBag = DB::table('cargo_bags')
            ->selectRaw('cargo_bags.*, (select count(*) from cargo_bag_details where bag_id = cargo_bags.id)  as included_cargo_count, users.name_surname')
            ->join('users', 'cargo_bags.creator_user_id', '=', 'users.id')
            ->where('cargo_bags.id', $cargoBag->id)
            ->first();

        if ($cargoBag->included_cargo_count != 0)
            return ['status' => '0', 'message' => 'Silme işlemini gerçkeleştirebilimeniz için kargo içeriğinin 0 olması gerekmektedir.'];
        else {
            $cargoBag = CargoBags::find($request->destroy_id)
                ->delete();
            return $cargoBag ? 1 : 0;
        }
    }

    public function getBagGeneralInfo(Request $request)
    {
        if ($request->id == '')
            return response()
                ->json(['status' => 0, 'message' => 'Barcod numarası alanı zorunludur!'], 200);

        $bag = CargoBags::find($request->id);

        if ($bag == null)
            return response()
                ->json(['status' => 0, 'message' => 'Torba & Çuval bulanamadı!'], 200);

        $bagInfo = DB::table('cargo_bags')
            ->where('id', $bag->id)
            ->first();

        $bagInfo->design_tracking_no = TrackingNumberDesign($bagInfo->tracking_no);
        $bagInfo->crypted_no = crypteTrackingNo("$bagInfo->tracking_no");
        $bagInfo->created_at = substr($bagInfo->created_at, 0, 16);
        $bagInfo->type = tr_strtoupper($bagInfo->type);


        $creator_user = User::find($bagInfo->creator_user_id);

        $departure_point = "";
        $arrival_point = "";
        if ($creator_user->user_type == 'Acente') {

            $agency = DB::table('view_agency_region')
                ->where('id', $creator_user->agency_code)
                ->first();

            $tc = TransshipmentCenters::find($agency->tc_id);

            $departure_point = $agency->agency_name;
            $arrival_point = $tc->tc_name . ' TRM.';
        }

        $bagInfo->departure_point = $departure_point;
        $bagInfo->arrival_point = $arrival_point;


        $data = [
            'status' => 1,
            'bag_info' => $bagInfo,
        ];

        return response()->json($data, 200);
    }

}




































