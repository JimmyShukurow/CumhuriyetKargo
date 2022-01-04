<?php

namespace App\Http\Controllers\Backend\ServiceFee;

use App\Http\Controllers\Controller;
use App\Models\AdditionalServices;
use App\Models\DesiList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DesiListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $DesiList = DesiList::all();

        return datatables()->of($DesiList)
            ->setRowId(function ($data) {
                return 'desi-interval-' . $data->id;
            })
            ->editColumn('desi_price', function ($data) {
                return '<b>' . '₺' . $data->desi_price . '</b>';
            })
            ->editColumn('corporate_unit_price', function ($data) {
                return '<b>' . '₺' . $data->corporate_unit_price . '</b>';
            })
            ->editColumn('individual_unit_price', function ($data) {
                return '<b>' . '₺' . $data->individual_unit_price . '</b>';
            })
            ->editColumn('mobile_individual_unit_price', function ($data) {
                return '<b>' . '₺' . $data->mobile_individual_unit_price . '</b>';
            })
            ->editColumn('created_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y H:i');
                return $formatedDate;
            })
            ->editColumn('updated_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->format('d-m-Y H:i');
                return $formatedDate;
            })
            ->addColumn('edit', 'backend.service_fee.columns.desi-list-edit')
            ->rawColumns(['edit', 'mobile_individual_unit_price', 'desi_price', 'corporate_unit_price', 'individual_unit_price'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->desi_price = substr($request->desi_price, 3, strlen($request->desi_price));
        $request->corporate_unit_price = substr($request->corporate_unit_price, 3, strlen($request->corporate_unit_price));
        $request->individual_unit_price = substr($request->individual_unit_price, 3, strlen($request->individual_unit_price));
        $request->mobile_individual_unit_price = substr($request->mobile_individual_unit_price, 3, strlen($request->mobile_individual_unit_price));

        $rules = [
            'start_desi' => 'required|numeric',
            'finish_desi' => 'required|numeric',
            'desi_price' => 'required',
            'corporate_unit_price' => 'required',
            'individual_unit_price' => 'required',
            'mobile_individual_unit_price' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);


        $store = DesiList::create([
            'start_desi' => $request->start_desi,
            'finish_desi' => $request->finish_desi,
            'desi_price' => $request->desi_price,
            'corporate_unit_price' => $request->corporate_unit_price,
            'individual_unit_price' => $request->individual_unit_price,
            'mobile_individual_unit_price' => $request->mobile_individual_unit_price,
        ]);

        if ($store)
            return response()->json(['status' => 1]);
        else
            return response()->json(['status' => 0, 'message' => 'Bilinmeyen bir hata oluştu!']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->desi_price = substr($request->desi_price, 3, strlen($request->desi_price));
        $request->corporate_unit_price = substr($request->corporate_unit_price, 3, strlen($request->corporate_unit_price));
        $request->individual_unit_price = substr($request->individual_unit_price, 3, strlen($request->individual_unit_price));
        $request->mobile_individual_unit_price = substr($request->mobile_individual_unit_price, 3, strlen($request->mobile_individual_unit_price));

        $rules = [
            'start_desi' => 'required|numeric',
            'finish_desi' => 'required|numeric',
            'desi_price' => 'required',
            'corporate_unit_price' => 'required',
            'individual_unit_price' => 'required',
            'mobile_individual_unit_price' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);

        $store = DesiList::find($id)
            ->update([
                'start_desi' => $request->start_desi,
                'finish_desi' => $request->finish_desi,
                'desi_price' => $request->desi_price,
                'corporate_unit_price' => $request->corporate_unit_price,
                'individual_unit_price' => $request->individual_unit_price,
                'mobile_individual_unit_price' => $request->mobile_individual_unit_price,
            ]);

        if ($store)
            return response()->json(['status' => 1]);
        else
            return response()->json(['status' => 0, 'message' => 'Bilinmeyen bir hata oluştu!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deleteRow(Request $request)
    {

        $deleteId = $request->destroy_id;
        $delete = DesiList::find($deleteId)
            ->delete();
        return $delete ? 1 : 0;
    }
}
















