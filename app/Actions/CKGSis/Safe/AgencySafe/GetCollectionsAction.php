<?php

namespace App\Actions\CKGSis\Safe\AgencySafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;
use Psy\Util\Str;

class GetCollectionsAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = Carbon::createFromDate($request->firstDate);
        $lastDate = Carbon::createFromDate($request->lastDate);
//        $dateFilter = $request->dateFilter;
        $dateFilter = 'true';


        if ($dateFilter == "true") {
            $diff = $firstDate->diffInDays($lastDate);
            if ($dateFilter) {
                if ($diff >= 90) {
                    return response()->json(['status' => 0, 'message' => 'Tarih aralığı max. 90 gün olabilir!'], 509);
                }
            }
        }

        $firstDate = substr($firstDate, 0, 10);
        $lastDate = substr($lastDate, 0, 10);


        $cargoes = DB::table('cargoes')
            ->join('currents', 'currents.id', '=', 'cargoes.sender_id')
            ->join('cargo_collections', 'cargo_collections.cargo_id', '=', 'cargoes.id')
            ->join('users', 'users.id', '=', 'cargo_collections.collection_entered_user_id')
            ->join('agencies', 'agencies.id', '=', 'users.agency_code')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select(['cargoes.*', 'agencies.city as city_name', 'cargo_collections.collection_type_entered', 'collection_entered_user_id', 'cargo_collections.description as collection_description', 'currents.current_code as sender_current_code', 'agencies.district as district_name', 'agencies.agency_name', 'users.name_surname as user_name_surname', 'roles.display_name'])
            ->whereRaw($dateFilter == 'true' ? "cargoes.created_at between '" . $firstDate . " 00:00:00'  and '" . $lastDate . " 23:59:59'" : ' 1 > 0')
            ->whereRaw('cargoes.deleted_at is null')
            ->where('cargoes.departure_agency_code', Auth::user()->agency_code)
            ->get();

        return datatables()->of($cargoes)
            ->editColumn('total_price', function ($key) {
                return '<b class="text-primary">' . $key->total_price . ' ₺</b>';
            })
            ->editColumn('sender_current_code', function ($key) {
                return '<b class="text-dark">' . CurrentCodeDesign($key->sender_current_code) . '</b>';
            })
            ->editColumn('invoice_date', function ($key) {
                return date('d/m/Y', strtotime($key->created_at));
            })
            ->editColumn('collection_type_entered', function ($key) {
                return '<b>' . $key->collection_type_entered . '</b>';
            })
            ->editColumn('collection_description', function ($key) {
                return '<span title="' . $key->collection_description . '">' . \Illuminate\Support\Str::words($key->collection_description, 3, '...') . '</span>';
            })
            ->editColumn('receiver_city', function ($key) {
                return $key->receiver_city . '/' . $key->receiver_district;
            })
            ->editColumn('receiver_name', function ($key) {
                return \Illuminate\Support\Str::words($key->receiver_name, 3, '...');
            })
            ->editColumn('user_name_surname', function ($key) {
                return $key->user_name_surname . ' (' . $key->display_name . ')';
            })
            ->addColumn('edit', 'backend.safe.agency.columns.edit')
            ->addColumn('invoice_number', 'backend.main_cargo.main.columns.invoice_number')
            ->rawColumns(['tracking_no', 'edit', 'collection_description', 'collection_type_entered', 'sender_current_code', 'invoice_number', 'agency_name', 'status_for_human', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }

}
