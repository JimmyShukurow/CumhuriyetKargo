<?php

namespace App\Actions\CKGSis\Region;

use App\Models\TransshipmentCenters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRegionCargoesAction
{
    use AsAction;

    public function handle($request)
    {

        $data['tc'] = TransshipmentCenters::find(Auth::user()->tc_code);
        $regionAgencies = DB::table('view_agency_region')
            ->select('id')
            ->where('tc_id', $data['tc']->id)
            ->get();

        $regionAgencies= $regionAgencies->pluck('id');

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
            ->join('view_agency_region', 'view_agency_region.id', '=', 'cargoes.departure_agency_code')
            ->select(['cargoes.*', 'view_agency_region.agency_name', 'view_agency_region.agency_code','users.name_surname'])
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
            ->whereIn('departure_agency_code', $regionAgencies);

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

}
