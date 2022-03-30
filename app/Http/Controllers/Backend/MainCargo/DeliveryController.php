<?php

namespace App\Http\Controllers\Backend\MainCargo;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $branch = [];
        $branch = getUserBranchInfo();

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Sefer oluştur modülü görüntülendi.');
        return view('backend.main_cargo.delivery.index', compact(['branch', 'agencies', 'tc']));
    }
}
