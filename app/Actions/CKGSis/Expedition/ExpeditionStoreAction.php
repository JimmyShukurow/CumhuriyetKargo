<?php

namespace App\Actions\CKGSis\Expedition;

use App\Models\Agencies;
use App\Models\Expedition;
use App\Models\ExpeditionRoute;
use App\Models\TcCars;
use App\Models\TransshipmentCenters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Lorisleiva\Actions\Concerns\AsAction;

class ExpeditionStoreAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'arrivalBranchType' => ['required', Rule::in(['Aktarma', 'Acente'])],
            'arrivalBranchCode' => 'required',
            'plaque' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

        $expeditionRoutes = $request->expeditionRoutes;

        $validateExpedition = ExpeditionRouteValidateAction::run($expeditionRoutes);
        if ($validateExpedition['status'] == -1)
            return response()->json($validateExpedition, 200);


        $plaque = tr_strtoupper($request->plaque);
        $car = TcCars::where('plaka', $plaque)->first();

        if ($car == null)
            return response()->json(['status' => '-1', 'message' => $plaque . ' plakalı araç sistemde kayıtlı değil!'], 200);

        if ($car->status != 1)
            return response()->json(['status' => '-1', 'message' => $plaque . ' plakalı araç pasif durumda!'], 200);

        if ($car->confirm == 0)
            return response()->json(['status' => '-1', 'message' => $plaque . ' plakalı araç onaylı değil!'], 200);


        DB::beginTransaction();

        try {
            $create = Expedition::create([
                'serial_no' => $this->createExpeditionSerialNumber(),
                'car_id' => $car->id,
                'user_id' => Auth::id(),
                'description' => tr_strtoupper($request->description),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => -1, 'message' => 'Sefer kayıt işlemi esnasında bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }

        try {
            $branch = getUserBranchInfo();

            $createRoute = ExpeditionRoute::create([
                'expedition_id' => $create->id,
                'branch_code' => $branch['id'],
                'branch_type' => $branch['type2'],
                'order' => 0,
                'route_type' => 1,
            ]);

            $count = 0;
            if (is_array($expeditionRoutes)) {
                foreach ($expeditionRoutes as $key) {
                    $createRoute = ExpeditionRoute::create([
                        'expedition_id' => $create->id,
                        'branch_code' => $key[1],
                        'branch_type' => $key[0],
                        'order' => ++$count,
                        'route_type' => 0,
                    ]);
                }
            }

            $createRoute = ExpeditionRoute::create([
                'expedition_id' => $create->id,
                'branch_code' => $request->arrivalBranchCode,
                'branch_type' => $request->arrivalBranchType,
                'order' => ++$count,
                'route_type' => -1,
            ]);


        } catch (Exception $e) {
            DB::rollBack();
            return response()
                ->json(['status' => -1, 'message' => 'Güzergah kayıt işlemi esnasında bir hata oluştu, lütfen daha sonra tekrar deneyiniz!'], 200);
        }

        DB::commit();
        return response()->json(['status' => 1, 'message' => 'İşlem başarılı, Sefer oluşturuldu!'], 200);
    }

    public function createExpeditionSerialNumber()
    {
        while (true) {
            $rand = rand(123456789, 987654321);

            $control = DB::table('expeditions')
                ->where('serial_no', $rand)
                ->first();

            if ($control == null)
                break;
            else
                continue;
        }

        return $rand;
    }
}
