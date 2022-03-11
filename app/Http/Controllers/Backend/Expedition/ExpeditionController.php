<?php

namespace App\Http\Controllers\Backend\Expedition;

use App\Actions\CKGMobile\Expedition\LoadCargoToExpeditionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expedition\LoadCargoToExpeditionRequest;
use App\Models\Agencies;
use App\Models\Cargoes;
use App\Models\Expedition;
use App\Models\ExpeditionCargo;
use App\Models\TransshipmentCenters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpeditionController extends Controller
{
    public function create()
    {
        $branch = [];
        ## Get Branch Info
        if (Auth::user()->user_type == 'Acente') {
            $agency = Agencies::find(Auth::user()->agency_code);
            $branch = [
                'code' => $agency->agency_code,
                'city' => $agency->city,
                'name' => $agency->agency_name,
                'type' => 'ŞUBE'
            ];
        } else {
            $tc = TransshipmentCenters::find(Auth::user()->tc_code);
            $branch = [
                'city' => $tc->city,
                'name' => $tc->tc_name,
                'type' => 'TRM.'
            ];
        }

        $agencies = Agencies::orderBy('agency_name')
            ->get();
        $tc = TransshipmentCenters::all();

        GeneralLog('Sefer oluştur modülü.');
        return view('backend.expedition.create.create', compact(['branch', 'agencies', 'tc']));
    }

    public function loadCargo(LoadCargoToExpeditionRequest $request){
        $validated = $request->validated();

        $ctn = decryptTrackingNo($validated['ctn']);
        $ctn = explode(' ', $ctn);


        $expedition = Expedition::find($validated['expedition_id']);

        if ($expedition->done == 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Bu Sefer Bittiğinden Dolayı İşlem Yapamazsınız',
            ]);
        }

        $cargo = Cargoes::where('tracking_no', $ctn[0])->first();
        $cargoes = ExpeditionCargo::where('cargo_id', $cargo->id)->where('unloading_at', null)->get()->pluck('part_no');
            if ($cargoes->contains($ctn[1])){
                return response()->json([
                    'status' => 0,
                    'message' => 'Bu Kargo Zaten Araçta Var',
                ]);
            }

        $fields = $request->only('expedition_id');
        $fields['cargo_id'] = $cargo->id;
        $fields['part_no'] = $ctn[1];
        $fields['user_id'] = Auth::id();

        return LoadCargoToExpeditionAction::run($fields);
    }
}
