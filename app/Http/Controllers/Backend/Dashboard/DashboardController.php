<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Agencies;
use App\Models\Districts;
use App\Models\RegioanalDirectorates;
use App\Models\RegionalDistricts;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function gmDashboard()
    {
        $data['agency_quantity'] = Agencies::count();
        $data['region_quantity'] = RegioanalDirectorates::count();
        $data['tc_quantity'] = TransshipmentCenters::count();
        $data['total_districts'] = $districts = Districts::count();
        $data['regional_districts'] = $regional_districts = RegionalDistricts::count();
        $data['idle_districts_quantity'] = $districts - $regional_districts;
        $data['regions'] = DB::table('view_regional_directorates_detail')
            ->orderBy('district_covered_quantity', 'desc')
            ->get();

        GeneralLog('Bölgesel Rapor (Operasyonel) görüntülendi.');
        return view('backend.dashboard.gm.index', compact('data'));
    }
}
