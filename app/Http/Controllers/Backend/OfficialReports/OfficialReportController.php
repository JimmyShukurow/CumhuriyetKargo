<?php

namespace App\Http\Controllers\Backend\OfficialReports;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Cities;
use App\Models\HtfDamageDetails;
use App\Models\HtfPieceDetails;
use App\Models\HtfTransactionDetails;
use App\Models\Reports;
use App\Models\TransshipmentCenters;
use App\Models\UtfImproprietyDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OfficialReportController extends Controller
{

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
        $permission = in_array(Auth::id(), $permissionIds);

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
        $permission = in_array(Auth::id(), $permissionIds);


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

    public function ourReports()
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


        GeneralLog('Tutanaklarım sayfası görüntülendi');
        return view('backend.OfficialReports.our_reports', compact(['data', 'unit', 'agencies', 'tc']));
    }

    public function getOurReports(Request $request)
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

        $cargoes = DB::table('reports')
            ->select(['reports.*', 'users.name_surname'])
            ->selectRaw("	reports.*,
                    users.name_surname,
                IF
                    (
                        reports.real_reported_unit_type = 'Acente',
                        CONCAT(( SELECT agency_name FROM agencies WHERE agencies.id = reports.reported_unit_id ), ' ŞUBE' ),
                        CONCAT(( SELECT tc_name FROM transshipment_centers WHERE id = reports.reported_unit_id ), ' TRM' )
                    ) AS reported_unit "
            )
            ->join('users', 'users.id', '=', 'reports.detecting_user_id');

        return datatables()->of($cargoes)
            ->editColumn('free', function () {
                return '';
            })
//            ->setRowId(function ($cargoes) {
//                return "cargo-item-" . $cargoes->id;
//            })
//            ->editColumn('payment_type', function ($cargoes) {
//                return $cargoes->payment_type == 'Gönderici Ödemeli' ? '<b class="text-alternate">' . $cargoes->payment_type . '</b>' : '<b class="text-dark">' . $cargoes->payment_type . '</b>';
//            })
//            ->editColumn('cargo_type', function ($cargoes) {
//                return $cargoes->cargo_type == 'Koli' ? '<b class="text-primary">' . $cargoes->cargo_type . '</b>' : '<b class="text-success">' . $cargoes->cargo_type . '</b>';
//            })
//            ->editColumn('receiver_address', function ($cargoes) {
//                return substr($cargoes->receiver_address, 0, 30);
//            })
//            ->editColumn('agency_name', function ($cargoes) {
//                return $cargoes->agency_name;
//            })
//            ->editColumn('sender_name', function ($cargoes) {
//                return substr($cargoes->sender_name, 0, 30);
//            })
//            ->editColumn('receiver_name', function ($cargoes) {
//                return substr($cargoes->receiver_name, 0, 30);
//            })
            ->editColumn('confirm', function ($key) {
                return $key->confirm == '1' ? '<b class="text-success">Onaylandı</b>' : '<b class="text-danger">Onay Bekliyor</b>';
            })
//            ->editColumn('total_price', function ($cargoes) {
//                return '<b class="text-primary">' . $cargoes->total_price . '₺' . '</b>';
//            })
//            ->editColumn('collection_fee', function ($cargoes) {
//                return '<b class="text-primary">' . $cargoes->collection_fee . '₺' . '</b>';
//            })
//            ->editColumn('status', function ($cargoes) {
//                return '<b class="text-dark">' . $cargoes->status . '</b>';
//            })
            ->editColumn('type', function ($key) {
                return $key->type == 'HTF' ? '<b class="text-primary">' . $key->type . '</b>' : '<b class="text-danger">' . $key->type . '</b>';
            })
            ->addColumn('detail', function ($key) {
                return '<a href="javascript:void(0)" class="btn btn-sm btn-primary">Detay</a>';
            })
//            ->editColumn('status_for_human', function ($cargoes) {
//                return '<b class="text-success">' . $cargoes->status_for_human . '</b>';
//            })
//            ->addColumn('edit', 'backend.marketing.sender_currents.columns.edit')
//            ->addColumn('tracking_no', 'backend.main_cargo.search_cargo.columns.tracking_no')
            ->addColumn('report_serial_no', 'backend.OfficialReports.columns.report_serial_no')
            ->rawColumns(['confirm', 'report_serial_no', 'type', 'detail', 'created_at', 'status', 'collection_fee', 'total_price', 'collectible', 'cargo_type', 'payment_type'])
            ->make(true);
    }


}
