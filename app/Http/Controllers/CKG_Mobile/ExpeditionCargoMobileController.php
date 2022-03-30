<?php

namespace App\Http\Controllers\CKG_Mobile;

use App\Actions\CKGMobile\Expedition\CargoExpeditionMovementAction;
use App\Actions\CKGMobile\Expedition\CheckCargoRouteAgencyLocationMatchAction;
use App\Actions\CKGMobile\Expedition\LoadCargoToExpeditionAction;
use App\Actions\CKGMobile\Expedition\UnloadCargoFromExpedition;
use App\Http\Controllers\Controller;
use App\Http\Requests\Expedition\LoadCargoToExpeditionRequest;
use App\Http\Resources\CKGMobile\Expedition\CargoResource;
use App\Http\Resources\CKGMobile\Expedition\ExpeditionResource;
use App\Models\CargoBags;
use App\Models\Cargoes;
use App\Models\Expedition;
use App\Models\ExpeditionCargo;
use App\Models\ExpeditionRoute;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpeditionCargoMobileController extends Controller
{
    public function loadCargo(LoadCargoToExpeditionRequest $request)
    {
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



        if ($cargo && ($cargo->cargo_type == 'Dosya' || $cargo->cargo_type == 'Mi'))
            return response()->json([
                'status' => 0,
                'message' => 'Dosya ve Mi kargoları sadece torba ile yükleyebilirsiniz!',
            ]);


        if ($cargo == null) {
            $cargoBag = CargoBags::where('tracking_no', $ctn)->first();
            if ($cargoBag == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Kargo bulunamadı!',
                ]);
            }
            $cargoes = $cargoBag->details()->get();
            $cargoes->map(function ($cargo) use ($expedition) {
                $user_id = Auth::id();
                $fields = [];
                $fields['expedition_id'] = $expedition->id;
                $fields['cargo_id'] = $cargo->cargo->id;
                $fields['part_no'] = $cargo->part_no;
                $fields['user_id'] = $user_id;
                CargoExpeditionMovementAction::run($cargo->cargo->tracking_no, $cargo->cargo, $user_id, $cargo->part_no, rand(4, 10), 1, 'load_cargo_expedition', $expedition->car->plaka);
                LoadCargoToExpeditionAction::run($fields);

            });
            return response()->json([
                'status' => 1,
                'message' => 'Torba Yüklendi!',
            ]);
        } elseif ($cargo->transporter != 'CK')
            return response()->json([
                'status' => 0,
                'message' => 'Bu kargonun taşımasını Cumhuriyet Kargo yapmadığından yükleme işlemi gerçekletiremezsiniz!',
            ]);

        #check by agencies locallocations from route id
        $route = ExpeditionRoute::find($request->route_id);
        if ($route->branch_type == 'Acente') {
            $check = CheckCargoRouteAgencyLocationMatchAction::run($route->branch, $cargo);
            if (! $check) {
                return [
                    'status' => 0,
                    'message' => 'Bu kargonun varış şubesi '. $cargo->arrivalBranchAgency->agency_name .' ŞUBE olduğundan '. $route->branch_details .'YE yükleme yapamazsınız.!'
                ];
            }
        }


        $user_id = Auth::id();
        $fields = $request->only('expedition_id');
        $fields['cargo_id'] = $cargo->id;
        $fields['part_no'] = $ctn[1];
        $fields['user_id'] = $user_id;
        CargoExpeditionMovementAction::run($ctn[0], $cargo, $user_id, $ctn[1], rand(4, 10), 1, 'load_cargo_expedition', $expedition->car->plaka);

        return LoadCargoToExpeditionAction::run($fields);
    }

    public function readExpedition(Request $request)
    {
        $plaka = tr_strtoupper($request->plaka);

        if ($plaka == null)
            return response()->json([
                'status' => 0,
                'message' => 'Plaka alanı zorunludur!',
            ]);

        $expedition = Expedition::with('car', 'routes.branch', 'cargoes.cargo')
            ->whereHas('car', function ($query) use ($plaka) {
                $query->where('plaka', $plaka);
            })
            ->where('done', 0)
            ->first();


        if ($expedition == null) {
            return response()->json([
                'status' => 0,
                'message' => 'Bu araca sefer oluşturulmamış!',
            ]);
        }

        if ($expedition->car->status != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Araç aktif değil, işlem yapamazsınız!',
            ]);
        }
        if ($expedition->car->confirm != 1) {
            return response()->json([
                'status' => 0,
                'message' => 'Araç onaylı değil, işlem yapamazsınız!',
            ]);
        }

        return response()->json([
            'status' => 1,
            'expedetion' => new ExpeditionResource($expedition),
            'cargoes' => CargoResource::collection($expedition->cargoes),

        ]);
    }

    public function unloadCargo(Request $request)
    {

        $ctn = decryptTrackingNo($request->ctn);
        $ctn = explode(' ', $ctn);
        $expedition = Expedition::find($request->expedition_id);

        $cargo = Cargoes::where('tracking_no', $ctn[0])->first();


        if ($cargo == null) {
            $cargoBag = CargoBags::where('tracking_no', $ctn)->first();
            if ($cargoBag == null) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Kargo bulunamadı!',
                ]);
            }
            $cargoes = $cargoBag->details;
            $cargoes->map(function ($cargo) use ($expedition) {
                $user_id = Auth::id();
                CargoExpeditionMovementAction::run($cargo->cargo->tracking_no, $cargo->cargo, $user_id, $cargo->part_no, rand(4, 10), 1, 'unload_cargo_expedition', $expedition->car->plaka);
                UnloadCargoFromExpedition::run($cargo->cargo_id, $cargo->part_no, $expedition->id);

            });
            return response()->json([
                'status' => 1,
                'message' => 'Torba İndirildi!',
            ]);
        }


        $cargo = $expedition->cargoes->filter(function ($item) use ($ctn) {
            return $item->cargo->tracking_no == $ctn[0];
        })->where('part_no', $ctn[1])->first();


        if ($cargo != null)
            $cargo->update([
                'unloading_user_id' => Auth::id(),
                'unloading_at' => now(),
            ]);
        else
            $cargo = Cargoes::where('tracking_no', $ctn[0])->first();


        $user_id = Auth::id();
        CargoExpeditionMovementAction::run($ctn[0], $cargo->cargo ?? $cargo, $user_id, $ctn[1], rand(4, 10), 1, 'unload_cargo_expedition', $expedition->car->plaka);

        return response()->json([
            'status' => 1,
            'message' => 'Kargo Indirildi!'
        ]);
    }

    public function deleteCargo(Request $request)
    {
        $ctn = decryptTrackingNo($request->ctn);
        $ctn = explode(' ', $ctn);

        $cargo = Cargoes::where('tracking_no', $ctn[0])->first();

        if ($cargo == null)
            return response()->json([
                'status' => 0,
                'message' => 'Kargo bulunamadı!',
            ]);

        if ($cargo->cargo_type == 'Dosya' || $cargo->cargo_type == 'Mi')
            return response()->json([
                'status' => 0,
                'message' => 'Çuval içerisindeki bir kargoyu silemezsiniz!',
            ]);


        $expedition = Expedition::with('cargoes.cargo')->where('id', $request->expedition_id)->first();

        $cargo = $expedition->cargoes->filter(function ($item) use ($ctn) {
            return $item->cargo->tracking_no == $ctn[0];
        })->where('part_no', $ctn[1])->first();

        $cargoLoader = $cargo->user;
        $userDeleter = Auth::user();

        $agencyControl =
            $cargoLoader->user_type == $userDeleter->user_type && ($cargoLoader->agency_code == $userDeleter->agency_code ||
                $cargoLoader->tc_code == $userDeleter->tc_code);
        if (!$agencyControl) {
            return response()->json([
                'status' => 0,
                'message' => 'Kargo Silme İşlemini Sadece Kargoyuyükleyen Birim Yapabilir!'
            ]);

        };


        if ($cargo == null) {
            return response()->json([
                'status' => 0,
                'message' => 'Böyle Bir Kargo Bulunamadı!'
            ]);
        }
        $cargo->update(['deleted_user_id' => auth()->id(), 'deleted_at' => now()]);

        $user_id = Auth::id();
        CargoExpeditionMovementAction::run($ctn[0], $cargo->cargo, $user_id, $ctn[1], rand(4, 10), 1, 'delete_cargo_expedition', $expedition->car->plaka);

        return response()->json([
            'status' => 1,
            'message' => 'Kargo Silindi!'
        ]);
    }

}
