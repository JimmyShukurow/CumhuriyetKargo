<?php

namespace App\Actions\CKGSis\Safe\AgencySafe;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPendingCollectionsAction
{
    use AsAction;

    public function handle($request)
    {
        $firstDate = new Carbon($request->firstDate);
        $lastDate = new Carbon($request->lastDate);
        $dateFilter = $request->dateFilter;

        $cargoes = DB::table('cargoes')
            ->join('users', 'users.id', '=', 'cargoes.creator_user_id')
            ->join('agencies', 'agencies.id', '=', 'users.agency_code')
            ->join('currents', 'currents.id', '=', 'cargoes.sender_id')
            ->join('cargo_collections', 'cargo_collections.cargo_id', '=', 'cargoes.id')
            ->select(['cargoes.*', 'agencies.city as city_name', 'currents.current_code as sender_current_code', 'agencies.district as district_name', 'agencies.agency_name', 'users.name_surname as user_name_surname'])
            ->whereRaw($dateFilter == 'true' ? "cargoes.created_at between '" . $firstDate . "'  and '" . $lastDate . "'" : ' 1 > 0')
            ->whereRaw('cargoes.deleted_at is null')
            ->where('cargo_collections.collection_entered', '=', 'HAYIR')
            ->where('cargoes.departure_agency_code', Auth::user()->agency_code)
            ->orderByDesc('created_at')
            ->get();

        return datatables()->of($cargoes)
            ->editColumn('total_price', function ($key) {
                return '<b class="text-primary">' . $key->total_price . ' ₺</b>';
            })
            ->editColumn('sender_current_code', function ($key) {
                return '<b class="text-dark">' . CurrentCodeDesign($key->sender_current_code) . '</b>';
            })
            ->editColumn('created_at', function ($key) {
                return date('d/m/Y', strtotime($key->created_at));
            })
            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
            ->addColumn('invoice_number', 'backend.main_cargo.main.columns.invoice_number')
            ->rawColumns(['tracking_no', 'sender_current_code', 'invoice_number', 'agency_name', 'status_for_human', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }
}
