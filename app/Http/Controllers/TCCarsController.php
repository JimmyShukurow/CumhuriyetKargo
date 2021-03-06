<?php

namespace App\Http\Controllers;

use App\Actions\CKGSis\Filo\AgencyCars\CarRejectSuccessAction;
use App\Actions\CKGSis\Filo\AgencyCars\CarConfirmSuccessAction;
use App\Actions\CKGSis\Filo\AgencyCars\GetAgencyCarsOfBranch;
use App\Actions\CKGSis\Filo\TCCars\GetTcCars;
use App\Http\Requests\TcCarsRequest;
use App\Models\Agencies;
use App\Models\TcCars;
use App\Models\TcCars as ModelsTcCars;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TCCarsController extends Controller
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
        $validated['confirm'] = '1';
        $validated['status'] = '1';
        $validated['creator_id'] = $user->id;

        $create = ModelsTcCars::create($validated);

        if ($create) {
            return back()
                ->with('success', 'Aktarma aracı başarıyla kaydedildi!');
        } else {
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
        }
    }


    public function show($id)
    {
        //
    }


    public function update(TcCarsRequest $request, $id)
    {
        $car = TcCars::find($id);
        $validated = $request->except(['branch_code', 'creator']);
        $update = $car->update($validated);

        if ($update) {
            return back()
                ->with('success', 'Aktarma aracı başarıyla degiştirildi!');
        } else {
            return back()
                ->with('error', 'Bir hata oluştu, lütfen daha sonra tekrar deneyin!');
        }
    }


    public function destroy($id)
    {
        //
    }

    public function edit ($id) {
        $car = TcCars::find($id);
        $transshipment_centers = TransshipmentCenters::select('id', 'tc_name')->get();

        return view(
            'backend.operation.tc_cars.edit',
            [
                'car' => $car,
                'transshipment_centers' => $transshipment_centers,
                'branch' => $car->transshipment,
                'creator' => $car->creator,


            ]
        );

    }
 public function editAgency ($id) {
        $car = TcCars::find($id);
        $transshipment_centers = TransshipmentCenters::select('id', 'tc_name')->get();

        return view(
            'backend.operation.tc_cars.edit_agency',
            [
                'car' => $car,
                'transshipment_centers' => $transshipment_centers,
                'branch' => $car->branch,
                'creator' => $car->creator,


            ]
        );

    }

    public function ajaxTcCars(Request $request, $val)
    {
        switch ($val) {
            case 'GetTransferCars':
                return GetTcCars::run($request);
            case 'AgencyBranchGetTransferCars':
                return GetAgencyCarsOfBranch::run($request);
            case 'CarConfirmSuccess':
                return CarConfirmSuccessAction::run($request);
            case 'CarRejectSuccess':
                return CarRejectSuccessAction::run($request);
        }
    }

    public function create()
    {
        $user = Auth::user();
        $transshipment_centers = TransshipmentCenters::select('id', 'tc_name')->get();
        GeneralLog('Aktarma aracı oluştur sayfası görüntülendi.');
        return view(
            'backend.operation.tc_cars.create',
            [
                'branch' => $user->transshipment->tc_name,
                'user' => $user->name_surname,
                'transshipment_centers' => $transshipment_centers,
            ]
        );
    }
}
