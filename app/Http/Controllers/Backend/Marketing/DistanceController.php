<?php

namespace App\Http\Controllers\Backend\Marketing;

use App\Http\Controllers\Controller;
use App\Models\DistancePrice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DistanceController extends Controller
{
    public function getDistancePrice(Request $request)
    {
        $DistancePrice = DistancePrice::all();

        return datatables()->of($DistancePrice)
            ->setRowId(function ($data) {
                return 'distance-price-' . $data->id;
            })
            ->editColumn('created_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y H:i:s');
                return $formatedDate;
            })
            ->editColumn('updated_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->format('d-m-Y H:i:s');
                return $formatedDate;
            })
            ->addColumn('edit', 'backend.service_fee.columns.desi-list-edit')
            ->rawColumns(['edit', 'desi_price', 'corporate_unit_price', 'individual_unit_price'])
            ->make(true);
    }
}
