<?php

namespace App\Http\Controllers\Backend\ServiceFee;

use App\Http\Controllers\Controller;
use App\Models\AdditionalServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdditionalServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AdditionalServices = AdditionalServices::all();

        return datatables()->of($AdditionalServices)
            ->setRowId(function ($data) {
                return 'additional-service-' . $data->id;
            })
            ->editColumn('status', function ($data) {
                return $data->status == '1' ? '<b class="text-success">Aktif</b>' : '<b class="text-danger">Pasif</b>';
            })
            ->editColumn('price', function ($data) {
                return '<b>' . '₺' . $data->price . '</b>';
            })
            ->editColumn('created_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y H:i');
                return $formatedDate;
            })
            ->editColumn('updated_at', function ($data) {
                $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_at)->format('d-m-Y H:i');
                return $formatedDate;
            })
            ->addColumn('edit', 'backend.service_fee.columns.additional-services-edit')
            ->rawColumns(['edit', 'price', 'status'])
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->price = substr($request->price, 3, strlen($request->price));

        $rules = [
            'service_name' => 'required',
            'price' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);


        $store = AdditionalServices::create([
            'service_name' => $request->service_name,
            'price' => $request->price
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
        //
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
        $request->price = substr($request->price, 3, strlen($request->price));

        $rules = [
            'service_name' => 'required',
            'price' => 'required',
            'status' => 'required|in:0,1'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '-1', 'errors' => $validator->getMessageBag()->toArray()], 200);


        $update = AdditionalServices::find($id)
            ->update([
                'service_name' => $request->service_name,
                'price' => $request->price,
                'status' => $request->status
            ]);

        if ($update)
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
        $destroy = AdditionalServices::find(intval($id))->delete();
        if ($destroy)
            return 1;

        return 0;
    }
}
