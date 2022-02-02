<?php

namespace App\Actions\CKGSis\MainCargo;

use App\Models\Districts;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetGlobalCargoesAction
{
    use AsAction;

    public function handle($request)
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
}
