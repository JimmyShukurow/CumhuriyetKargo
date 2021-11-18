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

            $invoiceNumber = $prefix . ' ' . $realRandom;

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
            'content_detection' => tr_strtoupper($request->icerikAciklamasi),
            'confirm' => '0',
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

            return response()
                ->json(['status' => 1, 'message' => 'Tutanak başarıyla oluşturuldu, Onay bekliyor.'], 200);
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
}
