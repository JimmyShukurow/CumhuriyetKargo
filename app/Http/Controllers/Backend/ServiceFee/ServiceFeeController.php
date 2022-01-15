<?php

namespace App\Http\Controllers\Backend\ServiceFee;

use App\Http\Controllers\Controller;
use App\Models\DesiList;
use App\Models\FilePrice;
use App\Models\ModuleGroups;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceFeeController extends Controller
{
    public function index()
    {
        $tab = 'AdditionalServices';
        $filePrice = FilePrice::first();
        return view('backend.service_fee.index', compact(['tab', 'filePrice']));
    }

    public function updateFilePrice(Request $request, $id)
    {
        $request->corporate_file_price = substr($request->corporate_file_price, 3, strlen($request->corporate_file_price));
        $request->individual_file_price = substr($request->individual_file_price, 3, strlen($request->individual_file_price));
        $request->mobile_file_price = substr($request->mobile_file_price, 3, strlen($request->mobile_file_price));

        $rules = [
            'corporate_file_price' => 'required',
            'individual_file_price' => 'required',
            'mobile_file_price' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);

        $store = FilePrice::find($id)
            ->update([
                'corporate_file_price' => $request->corporate_file_price,
                'individual_file_price' => $request->individual_file_price,
                'mobile_file_price' => $request->mobile_file_price,
            ]);

        if ($store)
            return response()->json(['status' => 1]);
        else
            return response()->json(['status' => 0, 'message' => 'Bilinmeyen bir hata oluştu!']);
    }

    public function updateMiPrice(Request $request, $id)
    {
        $request->corporate_mi_price = substr($request->corporate_mi_price, 3, strlen($request->corporate_mi_price));
        $request->individual_mi_price = substr($request->individual_mi_price, 3, strlen($request->individual_mi_price));
        $request->mobile_mi_price = substr($request->mobile_mi_price, 3, strlen($request->mobile_mi_price));

        $rules = [
            'corporate_mi_price' => 'required',
            'individual_mi_price' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);

        $store = FilePrice::find($id)
            ->update([
                'corporate_mi_price' => $request->corporate_mi_price,
                'individual_mi_price' => $request->individual_mi_price,
                'mobile_mi_price' => $request->mobile_mi_price,
            ]);

        if ($store)
            return response()->json(['status' => 1]);
        else
            return response()->json(['status' => 0, 'message' => 'Bilinmeyen bir hata oluştu!']);
    }

    public function getFilePrice()
    {
        $FilePrice = FilePrice::first();
        return response()->json(['status' => 1, 'price' => $FilePrice], 200);
    }

}
