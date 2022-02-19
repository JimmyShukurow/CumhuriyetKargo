<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\TransshipmentCenters;
use App\Models\Various;
use Illuminate\Http\Request;

class AgencyTransferCarsController extends Controller
{
    public function index()
    {
        $data['agencies'] = Various::all();
        $data['cities'] = Cities::all();
        $data['transshipment_centers'] = TransshipmentCenters::all();
        GeneralLog('Aktarma araçları sayfası görüntülendi.');
        return view('backend.operation.transfer_cars_agency.index', compact('data'));
    }
}
