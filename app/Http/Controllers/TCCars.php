<?php

namespace App\Http\Controllers;

use App\Actions\CKGSis\Filo\TCCars\GetTcCars;
use App\Http\Requests\TcCarsRequest;
use App\Models\Agencies;
use App\Models\TcCars as ModelsTcCars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TCCars extends Controller
{
    public function index()
    {
        GeneralLog('Genel Kasa görüntülendi.');

        $data['payment_channels'] = DB::table('view_agency_payment_app_details')
            ->groupBy('payment_channel')
            ->get();

        $data['agencies'] = Agencies::orderBy('agency_name')->get();


        return view('backend.operation.tc_cars.index', compact('data'));
    
    }

    public function store(TcCarsRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        $validated['branch_code'] = $user->transshipment->id;
        $validated['confirm'] = '0';
        $validated['creator_id'] = $user->id;

        $create = ModelsTcCars::create($validated);

        if ($create)
            return back()
                ->with('success', 'Aktarma aracı başarıyla kaydedildi!');
        else
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
    }

   
    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }


    public function ajaxTcCars(Request $request, $val)
    {
        logger("burdayim");
        switch ($val) {
            case 'GetTransferCars':
                return GetTcCars::run($request);
        } 
    }

    public function create()
    {
        $user = Auth::user();
        GeneralLog('Aktarma aracı oluştur sayfası görüntülendi.');
        return view('backend.operation.tc_cars.create', ['branch'=> $user->transshipment->tc_name, 'user' => $user->name_surname]);
    }
   
}
