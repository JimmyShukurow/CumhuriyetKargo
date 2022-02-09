<?php

namespace App\Http\Controllers\Backend\Region;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegionController extends Controller
{
    public function relationPlaces()
    {

        $data['cities'] = DB::table('transshipment_center_districts')
            ->orderBy('city')
            ->where('tc_id', Auth::user()->tc_code)
            ->get();


        $data['agencies'] = DB::table('view_agency_region')
            ->where('tc_id', Auth::user()->tc_code)
            ->orderBy('city')
            ->get();

        $data['tc'] = TransshipmentCenters::find(Auth::user()->tc_code);

        GeneralLog('Bölgeye bağlı yerler sayfası görüntülendi.');
        return view('backend.region.relation_places', compact('data'));
    }
}
