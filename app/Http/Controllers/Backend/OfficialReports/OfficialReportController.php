<?php

namespace App\Http\Controllers\Backend\OfficialReports;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;

class OfficialReportController extends Controller
{
    public function createHTF(Request $request)
    {
        $data['transshipment_centers'] = TransshipmentCenters::all();
        $data['cities'] = Cities::all();
        GeneralLog('HTF oluştur sayfası görüntülendi.');
        return view('backend.OfficialReports.htf_create', compact(['data']));
    }

}
