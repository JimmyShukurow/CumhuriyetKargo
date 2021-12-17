<?php

namespace App\Http\Controllers\Backend\OfficialReports;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\HtfDamageDetails;
use App\Models\HtfPieceDetails;
use App\Models\HtfTransactionDetails;
use App\Models\OfficialReports;
use App\Models\Reports;
use App\Models\TransshipmentCenters;
use App\Models\User;
use App\Models\UtfImproprietyDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OfficialReportController extends Controller
{

    public function index()
    {
        $data['cities'] = Cities::all();

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Tüm tutanaklar sayfası görüntülendi');
        return view('backend.OfficialReports.index', compact(['data', 'agencies', 'tc']));
    }

    public function getOfficialReports(Request $request)
    {
        $trackingNo = str_replace([' ', '_'], ['', ''], $request->filterTrackingNo);
        $ReportSerialNumber = $request->filterReportSerialNumber;
        $InvoiceNumber = $request->filterInvoiceNumber;
        $ReportType = $request->filterReportType;
        $StartDate = $request->filterStartDate;
        $FinishDate = $request->filterFinishDate;
        $SelectReportedAgency = $request->filterSelectReportedAgency;
        $SelectReportedTc = $request->filterSelectReportedTc;
        $DetectingUser = $request->filterDetectingUser;
        $Confirm = $request->filterConfirm;
        $Description = $request->filterDescription;

        $filterByDAte = $request->filterByDate;

        $startDate = new Carbon($StartDate);
        $finishDate = new Carbon($FinishDate);

        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 90) {
                    return response()->json([], 509);
                }
            }
        }

        $cargoes = DB::table('view_official_reports_general_info')
            ->whereRaw($trackingNo ? "cargo_tracking_no ='" . $trackingNo . "'" : ' 1 > 0')
            ->whereRaw($InvoiceNumber ? "( cargo_invoice_number ='" . $InvoiceNumber . "'" . " or description like '%" . $InvoiceNumber . "%')" : ' 1 > 0')
            ->whereRaw($ReportType ? "type ='" . $ReportType . "'" : ' 1 > 0')
            ->whereRaw($DetectingUser ? "name_surname  like '%" . $DetectingUser . "%'" : ' 1 > 0')
            ->whereRaw($Description ? "description  like '%" . $Description . "%'" : ' 1 > 0')
            ->whereRaw($SelectReportedAgency ? "real_detecting_unit_type='Acente' and reported_unit_id ='" . $SelectReportedAgency . "'" : ' 1 > 0')
            ->whereRaw($SelectReportedTc ? "real_detecting_unit_type='Aktarma' and reported_unit_id ='" . $SelectReportedTc . "'" : ' 1 > 0')
//            ->whereRaw($Confirm != '' ? "confirm ='" . $Confirm . "'" : ' 1 > 0')
            ->whereRaw($ReportSerialNumber ? "report_serial_no ='" . $ReportSerialNumber . "'" : ' 1 > 0')
            ->whereRaw($filterByDAte == "true" ? "created_at between '" . $StartDate . "' and '" . $FinishDate . "'" : ' 1 > 0')
            ->where('confirm', '1')
            ->limit(500)->get();

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
            ->setRowId(function ($cargoes) {
                return "report-item-" . $cargoes->id;
            })
            ->editColumn('confirm', function ($key) {
                if ($key->confirm == '1')
                    return '<b class="text-success">Onaylandı</b>';
                else if ($key->confirm == '0')
                    return '<b class="text-primary">Onay Bekliyor</b>';
                else if ($key->confirm == '-1')
                    return '<b class="text-danger">Onaylanmadı</b>';
            })
            ->editColumn('type', function ($key) {
                return $key->type == 'HTF' ? '<b class="text-primary">' . $key->type . '</b>' : '<b class="text-danger">' . $key->type . '</b>';
            })
            ->addColumn('detail', function ($key) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Detay</a>';
            })
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 3, '...') . '</span>';
            })
            ->addColumn('report_serial_no', 'backend.OfficialReports.columns.report_serial_no')
            ->rawColumns(['confirm', 'description', 'report_serial_no', 'type', 'detail', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }


    function rand_color()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public function createHTF(Request $request)
    {
        $damage_types = DB::table('htf_damage_types')->get();
        $transactions = DB::table('htf_transactions_made')->get();

        $branch = [];
        ## Get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $branch = [
                'code' => $agency->agency_code,
                'city' => $agency->city,
                'name' => $agency->agency_name,
                'type' => 'ŞUBE'
            ];
        } else {
            $tc = TransshipmentCenters::find(Auth::user()->tc_code);
            $branch = [
                'code' => $tc->tc_code,
                'city' => $tc->city,
                'name' => $tc->agency_name,
                'type' => 'TRM.'
            ];
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('HTF oluştur sayfası görüntülendi.');
        return view('backend.OfficialReports.htf_create', compact(['damage_types', 'transactions', 'branch', 'agencies', 'tc']));
    }

    function DesignReportInvoiceNumber()
    {
        $letters = ['A', 'X', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'U', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'V', 'Y', 'X', 'Z'];
        $invoiceNumber = "";

        while (true) {

            $rnd = rand(0, count($letters) - 1);
            $rnd2 = rand(0, count($letters) - 1);

            $prefix = $letters[$rnd] . $letters[$rnd2];
            $realRandom = rand(123456, 987654);

            $invoiceNumber = $prefix . '-' . $realRandom;

            #control Number
            $cargo = DB::table('reports')
                ->where('report_serial_no', $invoiceNumber)
                ->first();

            if ($cargo != null)
                continue;
            else
                break;
        }

        return $invoiceNumber;
    }

    public function insertHTF(Request $request)
    {
        $rules = [
            'faturaNumarasi' => 'required',
            'tutanakTutulanBirimTipi' => 'required',
            'tutanakTutulanBirim' => 'required',
            'icerikAciklamasi' => 'required',
            'hasarAciklamasi' => 'nullable',
            'hasarNedenleri' => 'required',
            'yapilanIslemler' => 'required',
            'ilgiliParcalar' => 'required',
            'tutanakTutulanBirimID' => 'nullable'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => -1, 'errors' => $validator->getMessageBag()->toArray()], 200);


        $cargo = Cargoes::where('invoice_number', $request->faturaNumarasi)
            ->first();

        if ($cargo == null)
            return response()
                ->json(['status' => 0, 'message' => 'Kargo bulunamadı!']);


        $parts = explode(',', $request->ilgiliParcalar);

        if (!is_array($parts) || $parts == null)
            return response()
                ->json(['status' => 0, 'message' => 'İlgili parçalar istenilen formatta değil!']);

        foreach ($parts as $key)
            if (!is_numeric($key)) {
                return response()
                    ->json(['status' => 0, 'message' => 'İlgili parçalar istenilen formatta değil!']);
                break;
            }

        $UnitTypes = ReportedUnitTypes();
        if (!in_array($request->tutanakTutulanBirimTipi, $UnitTypes))
            return response()
                ->json(['status' => 0, 'message' => 'Lütfen geçerli bir tutanak tutulan birimi tipi seçin!']);

        $realReportedUnitType = "";
        $reportedUnitID = false;


        switch ($request->tutanakTutulanBirimTipi) {
            case 'Varış Şube.':
                $realReportedUnitType = "Acente";
                $reportedUnitID = $cargo->arrival_agency_code;
                break;

            case 'Varış TRM.':
                $realReportedUnitType = "Aktarma";
                $reportedUnitID = $cargo->arrival_tc_code;
                break;

            case 'Çıkış Şube':
                $realReportedUnitType = "Acente";
                $reportedUnitID = $cargo->departure_agency_code;
                break;

            case 'Çıkış TRM.':
                $realReportedUnitType = "Aktarma";
                $reportedUnitID = $cargo->departure_tc_code;
                break;

            case 'Diğer Şube':
                $realReportedUnitType = "Acente";

                $agencyControl = Agencies::find($request->tutanakTutulanBirimID);
                if ($agencyControl == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Lütfen geçerli bir diğer şube seçin!']);

                $reportedUnitID = $agencyControl->id;
                break;

            case 'Diğer TRM.':
                $realReportedUnitType = "Aktarma";

                $tcControl = TransshipmentCenters::find($request->tutanakTutulanBirimID);
                if ($tcControl == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Lütfen geçerli bir diğer TRM. seçin!']);

                $reportedUnitID = $tcControl->id;

                break;
        }


        foreach ($request->hasarNedenleri as $key) {
            $control = DB::table('htf_damage_types')
                ->where('id', $key)
                ->first();

            if ($control == null)
                return response()
                    ->json(['status' => 0, 'message' => 'Lütfen geçerli bir hasar nedeni seçin!']);
        }

        foreach ($request->yapilanIslemler as $key) {
            $control = DB::table('htf_transactions_made')
                ->where('id', $key)
                ->first();

            if ($control == null)
                return response()
                    ->json(['status' => 0, 'message' => 'Lütfen geçerli bir yapılan işlem seçin!']);
        }

        #control permission
        $permissionIds = OfficialReportsPermissions();
        $permission = in_array(Auth::user()->role_id, $permissionIds);

        $createHTF = Reports::create([
            'type' => 'HTF',
            'report_serial_no' => $this->DesignReportInvoiceNumber(),
            'cargo_id' => $cargo->id,
            'cargo_invoice_number' => $cargo->invoice_number,
            'cargo_tracking_no' => $cargo->tracking_no,
            'real_detecting_unit_type' => Auth::user()->user_type,
            'detecting_user_id' => Auth::id(),
            'reported_unit_type' => $request->tutanakTutulanBirimTipi,
            'real_reported_unit_type' => $realReportedUnitType,
            'reported_unit_id' => $reportedUnitID,
            'damage_description' => tr_strtoupper($request->hasarAciklamasi),
            'description' => tr_strtoupper($request->hasarAciklamasi),
            'content_detection' => tr_strtoupper($request->icerikAciklamasi),
            'confirm' => $permission ? '1' : '0',
            'confirming_user_id' => $permission ? Auth::id() : null,
            'confirming_datetime' => $permission ? Carbon::now() : null,
        ]);

        if ($createHTF) {
            foreach ($parts as $key)
                $insertParts = HtfPieceDetails::create([
                    'htf_id' => $createHTF->id,
                    'cargo_id' => $cargo->id,
                    'part_no' => $key
                ]);

            foreach ($request->hasarNedenleri as $key) {
                $control = DB::table('htf_damage_types')
                    ->where('id', $key)
                    ->first();
                $insert = HtfDamageDetails::create([
                    'htf_id' => $createHTF->id,
                    'damage_id' => $control->id,
                    'damage_text' => $control->damage_name
                ]);
            }

            foreach ($request->yapilanIslemler as $key) {
                $control = DB::table('htf_transactions_made')
                    ->where('id', $key)
                    ->first();

                $insert = HtfTransactionDetails::create([
                    'htf_id' => $createHTF->id,
                    'transaction_id' => $control->id,
                    'transaction_text' => $control->transaction_name
                ]);
            }

            if ($permission)
                $message = 'Tutanak başarıyla oluşturuldu ve onaylandı!';
            else
                $message = 'Tutanak başarıyla oluşturuldu, Onay bekliyor.';

            return response()
                ->json(['status' => 1, 'message' => $message], 200);
        }

        return response()
            ->json(['status' => 0, 'message' => 'İşlem başarısız oldu, lütfen daha sonra tekrar deneyiniz!']);
    }

    public function createUTF(Request $request)
    {
        $damage_types = DB::table('htf_damage_types')->get();
        $impropriety_types = DB::table('utf_impropriety_types')
            ->get();

        $transactions = DB::table('htf_transactions_made')->get();

        $branch = [];
        ## Get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $branch = [
                'code' => $agency->agency_code,
                'city' => $agency->city,
                'name' => $agency->agency_name,
                'type' => 'ŞUBE'
            ];
        } else {
            $tc = TransshipmentCenters::find(Auth::user()->tc_code);
            $branch = [
                'code' => $tc->tc_code,
                'city' => $tc->city,
                'name' => $tc->agency_name,
                'type' => 'TRM.'
            ];
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('UTF oluştur sayfası görüntülendi.');
        return view('backend.OfficialReports.utf_create', compact(['impropriety_types', 'transactions', 'branch', 'agencies', 'tc']));
    }

    public function insertUTF(Request $request)
    {
        $rules = [
            'tutanakTutulanBirimTipi' => 'required',
            'tutanakTutulanBirim' => 'required',
            'uygunsuzlukAciklamasi' => 'required',
            'uygunsuzlukNedenleri' => 'required',
            'tutanakTutulanBirimID' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => -1, 'errors' => $validator->getMessageBag()->toArray()], 200);

        $UnitTypes = ReportedUnitTypes();
        if (!in_array($request->tutanakTutulanBirimTipi, $UnitTypes))
            return response()
                ->json(['status' => 0, 'message' => 'Lütfen geçerli bir tutanak tutulan birimi tipi seçin!']);

        $realReportedUnitType = "";
        $reportedUnitID = false;


        switch ($request->tutanakTutulanBirimTipi) {

            case 'Diğer Şube':
                $realReportedUnitType = "Acente";

                $agencyControl = Agencies::find($request->tutanakTutulanBirimID);
                if ($agencyControl == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Lütfen geçerli bir diğer şube seçin!']);

                $reportedUnitID = $agencyControl->id;
                break;

            case 'Diğer TRM.':
                $realReportedUnitType = "Aktarma";

                $tcControl = TransshipmentCenters::find($request->tutanakTutulanBirimID);
                if ($tcControl == null)
                    return response()
                        ->json(['status' => 0, 'message' => 'Lütfen geçerli bir diğer TRM. seçin!']);

                $reportedUnitID = $tcControl->id;

                break;
        }


        foreach ($request->uygunsuzlukNedenleri as $key) {
            $control = DB::table('utf_impropriety_types')
                ->where('id', $key)
                ->first();

            if ($control == null)
                return response()
                    ->json(['status' => 0, 'message' => 'Lütfen geçerli bir hasar nedeni seçin!']);
        }

        #control permission
        $permissionIds = OfficialReportsPermissions();
        $permission = in_array(Auth::user()->role_id, $permissionIds);


        $createUTF = Reports::create([
            'type' => 'UTF',
            'report_serial_no' => $this->DesignReportInvoiceNumber(),
            'real_detecting_unit_type' => Auth::user()->user_type,
            'detecting_user_id' => Auth::id(),
            'reported_unit_type' => $request->tutanakTutulanBirimTipi,
            'real_reported_unit_type' => $realReportedUnitType,
            'reported_unit_id' => $reportedUnitID,
            'impropriety_description' => tr_strtoupper($request->uygunsuzlukAciklamasi),
            'description' => tr_strtoupper($request->uygunsuzlukAciklamasi),
            'confirm' => $permission ? '1' : '0',
            'confirming_user_id' => $permission ? Auth::id() : null,
            'confirming_datetime' => $permission ? Carbon::now() : null,
        ]);

        if ($createUTF) {

            foreach ($request->uygunsuzlukNedenleri as $key) {
                $control = DB::table('utf_impropriety_types')
                    ->where('id', $key)
                    ->first();
                $insert = UtfImproprietyDetails::create([
                    'utf_id' => $createUTF->id,
                    'impropriety_id' => $control->id,
                    'impropriety_text' => $control->name
                ]);
            }

            if ($permission)
                $message = 'Tutanak başarıyla oluşturuldu ve onaylandı!';
            else
                $message = 'Tutanak başarıyla oluşturuldu, Onay bekliyor.';

            return response()
                ->json(['status' => 1, 'message' => $message], 200);
        }

        return response()
            ->json(['status' => 0, 'message' => 'İşlem başarısız oldu, lütfen daha sonra tekrar deneyiniz!']);
    }

    public function outgoingReports()
    {
        $data['cities'] = Cities::all();
        $unit = '';

        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $unit = $agency->agency_name . ' ŞUBE';
        } else if (Auth::user()->user_type == 'Aktarma') {
            $agency = TransshipmentCenters::find(Auth::user()->tc_code);
            $unit = $agency->tc_name . ' TRM';
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('Giden tutanaklar sayfası görüntülendi');
        return view('backend.OfficialReports.outgoing_reports', compact(['data', 'unit', 'agencies', 'tc']));
    }

    public function getOutGoingReports(Request $request)
    {

        $unit = "";
        $unitID = 0;
        # get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $unit = Auth::user()->user_type;
            $unitID = Auth::user()->agency_code;
        } else if (Auth::user()->user_type == 'Aktarma') {
            $unit = Auth::user()->user_type;
            $unitID = Auth::user()->tc_code;
        }
//        return $unit . ' => ' . $unitID;

        $trackingNo = str_replace([' ', '_'], ['', ''], $request->filterTrackingNo);
        $ReportSerialNumber = $request->filterReportSerialNumber;
        $InvoiceNumber = $request->filterInvoiceNumber;
        $ReportType = $request->filterReportType;
        $StartDate = $request->filterStartDate;
        $FinishDate = $request->filterFinishDate;
        $SelectReportedAgency = $request->filterSelectReportedAgency;
        $SelectReportedTc = $request->filterSelectReportedTc;
        $DetectingUser = $request->filterDetectingUser;
        $Confirm = $request->filterConfirm;
        $Description = $request->filterDescription;

        $filterByDAte = $request->filterByDate;

        $startDate = new Carbon($StartDate);
        $finishDate = new Carbon($FinishDate);

        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 90) {
                    return response()->json([], 509);
                }
            }
        }

        $cargoes = DB::table('view_official_reports_general_info')
            ->whereRaw($trackingNo ? "cargo_tracking_no ='" . $trackingNo . "'" : ' 1 > 0')
            ->whereRaw($InvoiceNumber ? "cargo_invoice_number ='" . $InvoiceNumber . "'" . " or description like '%" . $InvoiceNumber . "%'" : ' 1 > 0')
            ->whereRaw($ReportType ? "type ='" . $ReportType . "'" : ' 1 > 0')
            ->whereRaw($DetectingUser ? "users.name_surname  like '%" . $DetectingUser . "%'" : ' 1 > 0')
            ->whereRaw($Description ? "description  like '%" . $Description . "%'" : ' 1 > 0')
            ->whereRaw($SelectReportedAgency ? "real_detecting_unit_type='Acente' and reported_unit_id ='" . $SelectReportedAgency . "'" : ' 1 > 0')
            ->whereRaw($SelectReportedTc ? "real_detecting_unit_type='Aktarma' and reported_unit_id ='" . $SelectReportedTc . "'" : ' 1 > 0')
            ->whereRaw($Confirm != '' ? "confirm ='" . $Confirm . "'" : ' 1 > 0')
            ->whereRaw($ReportSerialNumber ? "report_serial_no ='" . $ReportSerialNumber . "'" : ' 1 > 0')
            ->whereRaw($filterByDAte == "true" ? "created_at between '" . $StartDate . "' and '" . $FinishDate . "'" : ' 1 > 0')
            ->whereRaw("real_detecting_unit_type ='" . $unit . "'")
            ->where('detecting_unit_id', $unitID)
            ->limit(500)->get();

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
            ->setRowId(function ($cargoes) {
                return "report-item-" . $cargoes->id;
            })
            ->editColumn('confirm', function ($key) {
                if ($key->confirm == '1')
                    return '<b class="text-success">Onaylandı</b>';
                else if ($key->confirm == '0')
                    return '<b class="text-primary">Onay Bekliyor</b>';
                else if ($key->confirm == '-1')
                    return '<b class="text-danger">Onaylanmadı</b>';
            })
            ->editColumn('type', function ($key) {
                return $key->type == 'HTF' ? '<b class="text-primary">' . $key->type . '</b>' : '<b class="text-danger">' . $key->type . '</b>';
            })
            ->addColumn('detail', function ($key) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Detay</a>';
            })
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 3, '...') . '</span>';
            })
            ->addColumn('report_serial_no', 'backend.OfficialReports.columns.report_serial_no')
            ->rawColumns(['confirm', 'description', 'report_serial_no', 'type', 'detail', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }

    public function getReportInfo(Request $request)
    {
        $report = Reports::find($request->id);

        if ($report == null)
            return response()
                ->json(['status' => 0, 'message' => 'Rapor bulunamadı!']);

        $report = DB::table('reports')
            ->selectRaw("	reports.*,
                    view_users_all_info.name_surname, view_users_all_info.display_name,
                IF
                    (
                        reports.real_reported_unit_type = 'Acente',
                        CONCAT(( SELECT agency_name FROM agencies WHERE agencies.id = reports.reported_unit_id ), ' ŞUBE' ),
                        CONCAT(( SELECT tc_name FROM transshipment_centers WHERE id = reports.reported_unit_id ), ' TRM' )
                    ) AS reported_unit"
            )
            ->where('reports.id', $report->id)
            ->join('view_users_all_info', 'view_users_all_info.id', '=', 'reports.detecting_user_id')
            ->first();

        $user = User::find($report->detecting_user_id);

        if ($report->real_detecting_unit_type == 'Acente') {
            $agency = Agencies::find($user->agency_code);
            $detectingUnit = '#' . $agency->agency_code . " - " . $agency->agency_name . ' ŞUBE';
        } else {
            $tc = TransshipmentCenters::find($user->tc_code);
            $detectingUnit = $tc->tc_name . ' TRM.';
        }

        $improprietyDetailsString = "";
        $damageDetailsString = "";
        $transactionMadeString = "";
        $pieceDetails = [];
        if ($report->type == 'HTF') {
            $pieceDetails = HtfPieceDetails::where('htf_id', $report->id)->select('part_no')->get();
            $damageDetails = HtfDamageDetails::where('htf_id', $report->id)->get();

            foreach ($damageDetails as $key)
                $damageDetailsString .= $key->damage_text . ', ';

            $transactionDetails = HtfTransactionDetails::where('htf_id', $report->id)->get();
            foreach ($transactionDetails as $key)
                $transactionMadeString .= $key->transaction_text . ', ';
        } else if ($report->type == 'UTF') {
            $improprietyDetails = UtfImproprietyDetails::where('utf_id', $report->id)->get();

            foreach ($improprietyDetails as $key)
                $improprietyDetailsString .= $key->impropriety_text . ', ';
        }

        if ($report->confirming_user_id != '') {
            $ConfirmingUser = DB::table('view_users_all_info')->where('id', $report->confirming_user_id)->first();
            $report->confirming_user = $ConfirmingUser->name_surname . ' (' . $ConfirmingUser->display_name . ')';
        } else
            $report->confirming_user = "";

        if ($report->confirming_datetime != '')
            $report->confirming_datetime = date_format(date_create($report->confirming_datetime), 'd/m/Y H:i');


        $report->created_at_date = date_format(date_create($report->created_at), 'd/m/Y H:i');
        $report->detecting_unit = $detectingUnit;
        $report->damage_details = $damageDetailsString;
        $report->transaction_details = $transactionMadeString;
        $report->impropriety_details = $improprietyDetailsString;


        return response()
            ->json(['status' => 1, 'report' => $report, 'piece_details' => $pieceDetails]);
    }


    public function incomingReports()
    {
        $data['cities'] = Cities::all();
        $unit = '';

        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $unit = $agency->agency_name . ' ŞUBE';
        } else if (Auth::user()->user_type == 'Aktarma') {
            $agency = TransshipmentCenters::find(Auth::user()->tc_code);
            $unit = $agency->tc_name . ' TRM';
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('Giden tutanaklar sayfası görüntülendi');
        return view('backend.OfficialReports.incoming_reports', compact(['data', 'unit', 'agencies', 'tc']));
    }


    public function getIncomingReports(Request $request)
    {
        $unit = "";
        $unitID = 0;
        # get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $unit = Auth::user()->user_type;
            $unitID = Auth::user()->agency_code;
        } else if (Auth::user()->user_type == 'Aktarma') {
            $unit = Auth::user()->user_type;
            $unitID = Auth::user()->tc_code;
        }

//        return $unit . ' => ' . $unitID;

        $trackingNo = str_replace([' ', '_'], ['', ''], $request->filterTrackingNo);
        $ReportSerialNumber = $request->filterReportSerialNumber;
        $InvoiceNumber = $request->filterInvoiceNumber;
        $ReportType = $request->filterReportType;
        $StartDate = $request->filterStartDate;
        $FinishDate = $request->filterFinishDate;
        $SelectReportedAgency = $request->filterSelectReportedAgency;
        $SelectReportedTc = $request->filterSelectReportedTc;
        $DetectingUser = $request->filterDetectingUser;
        $Confirm = $request->filterConfirm;
        $Description = $request->filterDescription;

        $filterByDAte = $request->filterByDate;

        $startDate = new Carbon($StartDate);
        $finishDate = new Carbon($FinishDate);

        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 90) {
                    return response()->json([], 509);
                }
            }
        }

        $cargoes = DB::table('view_official_reports_general_info')
            ->whereRaw($trackingNo ? "cargo_tracking_no ='" . $trackingNo . "'" : ' 1 > 0')
            ->whereRaw($InvoiceNumber ? "cargo_invoice_number ='" . $InvoiceNumber . "'" . " or description like '%" . $InvoiceNumber . "%'" : ' 1 > 0')
            ->whereRaw($ReportType ? "type ='" . $ReportType . "'" : ' 1 > 0')
            ->whereRaw($DetectingUser ? "users.name_surname  like '%" . $DetectingUser . "%'" : ' 1 > 0')
            ->whereRaw($Description ? "description  like '%" . $Description . "%'" : ' 1 > 0')
            ->whereRaw($SelectReportedAgency ? "real_detecting_unit_type='Acente' and reported_unit_id ='" . $SelectReportedAgency . "'" : ' 1 > 0')
            ->whereRaw($SelectReportedTc ? "real_detecting_unit_type='Aktarma' and reported_unit_id ='" . $SelectReportedTc . "'" : ' 1 > 0')
            ->whereRaw($Confirm != '' ? "confirm ='" . $Confirm . "'" : ' 1 > 0')
            ->whereRaw($ReportSerialNumber ? "report_serial_no ='" . $ReportSerialNumber . "'" : ' 1 > 0')
            ->whereRaw($filterByDAte == "true" ? "created_at between '" . $StartDate . "' and '" . $FinishDate . "'" : ' 1 > 0')
            ->whereRaw("real_reported_unit_type ='" . $unit . "'")
            ->where('reported_unit_id', $unitID)
            ->limit(500)->get();

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
            ->setRowId(function ($cargoes) {
                return "report-item-" . $cargoes->id;
            })
            ->editColumn('confirm', function ($key) {
                if ($key->confirm == '1')
                    return '<b class="text-success">Onaylandı</b>';
                else if ($key->confirm == '0')
                    return '<b class="text-primary">Onay Bekliyor</b>';
                else if ($key->confirm == '-1')
                    return '<b class="text-danger">Onaylanmadı</b>';
            })
            ->editColumn('type', function ($key) {
                return $key->type == 'HTF' ? '<b class="text-primary">' . $key->type . '</b>' : '<b class="text-danger">' . $key->type . '</b>';
            })
            ->addColumn('detail', function ($key) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Detay</a>';
            })
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 3, '...') . '</span>';
            })
            ->addColumn('report_serial_no', 'backend.OfficialReports.columns.report_serial_no')
            ->rawColumns(['confirm', 'description', 'report_serial_no', 'type', 'detail', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }

    public function manageReport()
    {
        $data['cities'] = Cities::all();
        $unit = '';

        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $unit = $agency->agency_name . ' ŞUBE';
        } else if (Auth::user()->user_type == 'Aktarma') {
            $agency = TransshipmentCenters::find(Auth::user()->tc_code);
            $unit = $agency->tc_name . ' TRM';
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();


        GeneralLog('Tutanak onay sayfası görüntülendi');
        return view('backend.OfficialReports.manage_reports', compact(['data', 'unit', 'agencies', 'tc']));
    }

    public function getManageReports(Request $request)
    {

        $unit = "";
        $unitID = 0;
        # get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $unit = Auth::user()->user_type;
            $unitID = Auth::user()->agency_code;
        } else if (Auth::user()->user_type == 'Aktarma') {
            $unit = Auth::user()->user_type;
            $unitID = Auth::user()->tc_code;
        }
//        return $unit . ' => ' . $unitID;

        $trackingNo = str_replace([' ', '_'], ['', ''], $request->filterTrackingNo);
        $ReportSerialNumber = $request->filterReportSerialNumber;
        $InvoiceNumber = $request->filterInvoiceNumber;
        $ReportType = $request->filterReportType;
        $StartDate = $request->filterStartDate;
        $FinishDate = $request->filterFinishDate;
        $SelectReportedAgency = $request->filterSelectReportedAgency;
        $SelectReportedTc = $request->filterSelectReportedTc;
        $DetectingUser = $request->filterDetectingUser;
        $Confirm = $request->filterConfirm;
        $Description = $request->filterDescription;

        $filterByDAte = $request->filterByDate;

        $startDate = new Carbon($StartDate);
        $finishDate = new Carbon($FinishDate);

        if ($filterByDAte == "true") {
            $diff = $startDate->diffInDays($finishDate);
            if ($filterByDAte) {
                if ($diff >= 90) {
                    return response()->json([], 509);
                }
            }
        }

        $cargoes = DB::table('view_official_reports_general_info')
            ->whereRaw($trackingNo ? "cargo_tracking_no ='" . $trackingNo . "'" : ' 1 > 0')
            ->whereRaw($InvoiceNumber ? "cargo_invoice_number ='" . $InvoiceNumber . "'" . " or description like '%" . $InvoiceNumber . "%'" : ' 1 > 0')
            ->whereRaw($ReportType ? "type ='" . $ReportType . "'" : ' 1 > 0')
            ->whereRaw($DetectingUser ? "users.name_surname  like '%" . $DetectingUser . "%'" : ' 1 > 0')
            ->whereRaw($Description ? "description  like '%" . $Description . "%'" : ' 1 > 0')
            ->whereRaw($SelectReportedAgency ? "real_detecting_unit_type='Acente' and reported_unit_id ='" . $SelectReportedAgency . "'" : ' 1 > 0')
            ->whereRaw($SelectReportedTc ? "real_detecting_unit_type='Aktarma' and reported_unit_id ='" . $SelectReportedTc . "'" : ' 1 > 0')
            ->whereRaw($Confirm != '' ? "confirm ='" . $Confirm . "'" : ' 1 > 0')
            ->whereRaw($ReportSerialNumber ? "report_serial_no ='" . $ReportSerialNumber . "'" : ' 1 > 0')
            ->whereRaw($filterByDAte == "true" ? "created_at between '" . $StartDate . "' and '" . $FinishDate . "'" : ' 1 > 0')
            ->whereRaw("real_detecting_unit_type ='" . $unit . "'")
            ->where('detecting_unit_id', $unitID)
            ->limit(500)->get();

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
            ->setRowId(function ($key) {
                return "report-item-" . $key->id;
            })
            ->editColumn('confirm', function ($key) {
                if ($key->confirm == '1')
                    return '<b class="text-success">Onaylandı</b>';
                else if ($key->confirm == '0')
                    return '<b class="text-primary">Onay Bekliyor</b>';
                else if ($key->confirm == '-1')
                    return '<b class="text-danger">Onaylanmadı</b>';
            })
            ->editColumn('type', function ($key) {
                return $key->type == 'HTF' ? '<b class="text-primary">' . $key->type . '</b>' : '<b class="text-danger">' . $key->type . '</b>';
            })
            ->addColumn('detail', function ($key) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Detay</a>';
            })
            ->editColumn('description', function ($key) {
                return '<span title="' . $key->description . '">' . Str::words($key->description, 3, '...') . '</span>';
            })
            ->editColumn('check', function ($t) {
                return '<span class="unselectable">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
            })
            ->addColumn('report_serial_no', 'backend.OfficialReports.columns.report_serial_no')
            ->rawColumns(['confirm', 'check', 'description', 'report_serial_no', 'type', 'detail', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }

}













