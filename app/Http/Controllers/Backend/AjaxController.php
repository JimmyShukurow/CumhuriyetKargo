<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Districts;
use App\Models\Neighborhoods;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    public function cityToDistrict(Request $request)
    {
        $data = Districts::select('id as key', 'district_name as name')
            ->where('city_id', $request->city_id)
            ->get();

        return response()->json($data, 200);
    }

    public function districtToNeighborhood(Request $request)
    {
        $data = Neighborhoods::select('id as key', 'neighborhood_name as name')
            ->where('district_id', $request->district_id)
            ->get();

        return response()->json($data, 200);
    }

}
