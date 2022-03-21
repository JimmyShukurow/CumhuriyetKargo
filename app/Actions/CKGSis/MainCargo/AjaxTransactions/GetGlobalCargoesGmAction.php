<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Districts;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetGlobalCargoesGmAction
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

        $departureAgency = $request->departureAgency;
        $departureAgencyCode = $request->departureAgencyCode;
        $departureRegion = $request->departureRegion;
        $arrivalAgency = $request->arrivalAgency;
        $arrivalAgencyCode = $request->arrivalAgencyCode;
        $arrivalRegion = $request->arrivalRegion;
        $transporter = $request->transporter;


        $finishDate = new Carbon($finishDate);
        $startDate = new Carbon($startDate);

        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 90) {
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
            ->join('view_agency_region', 'view_agency_region.id', '=', 'cargoes.departure_agency_code')
            ->select(['cargoes.id', 'cargoes.tracking_no', 'cargoes.collectible','cargoes.departure_agency_code', 'cargoes.invoice_number', 'cargoes.created_at', 'cargoes.sender_name', 'cargoes.receiver_name', 'cargoes.sender_city', 'cargoes.receiver_city', 'cargoes.receiver_district', 'cargoes.cargo_type', 'cargoes.payment_type', 'cargoes.total_price', 'cargoes.status', 'view_agency_region.city as city_name', 'view_agency_region.agency_code as departure_real_agency_code', 'view_agency_region.district as district_name', 'view_agency_region.agency_name', 'users.name_surname as user_name_surname', 'view_agency_region.tc_id'])
            ->whereRaw($cargoType ? "cargo_type='" . $cargoType . "'" : '1 > 0')
            ->whereRaw($currentCity ? "sender_city='" . $currentCity . "'" : '1 > 0')
            ->whereRaw($currentDistrict ? "sender_district='" . $currentDistrict . "'" : '1 > 0')
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($departureAgency ? 'cargoes.departure_agency_code=' . $departureAgency : '1 > 0')
            ->whereRaw($departureAgencyCode ? 'view_agency_region.agency_code=' . $departureAgencyCode : '1 > 0')
            ->whereRaw($departureRegion ? 'departure_tc_code=' . $departureRegion : '1 > 0')
            ->whereRaw($arrivalAgency ? 'arrival_agency_code=' . $arrivalAgency : '1 > 0')
            ->whereRaw($arrivalAgencyCode ? 'arrival_agency_code=' . $arrivalAgencyCode : '1 > 0')
//            ->whereRaw($arrivalRegion ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($transporter ? "transporter='" . $transporter . "'" : '1 > 0')
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
            ->limit(1000)
            ->orderByDesc('created_at')
            ->get();

        return datatables()->of($cargoes)
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
            ->addColumn('invoice_number', 'backend.main_cargo.main.columns.invoice_number')
            ->rawColumns(['invoice_number', 'agency_name'])
            ->make(true);
    }

}
