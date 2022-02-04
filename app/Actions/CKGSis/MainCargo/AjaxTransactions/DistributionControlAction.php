<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Currents;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DistributionControlAction
{
    use AsAction;

    public function handle($request)
    {


        $array = [
            'status' => 1,
            'arrival_agency' => '-',
            'arrival_tc' => '-',
            'area_type' => 'YURTİÇİ KARGO',
        ];

        return response()
            ->json($array, 200);


        if ($request->currentCode == '' || $request->receiverCode == '')
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Alıcı ve Gönderici cari kodu bilgileri zorunludur!'
                ]);

        $currentCode = str_replace(' ', '', $request->currentCode);
        $receiverCode = str_replace(' ', '', $request->receiverCode);

        $receiver = Currents::where('current_code', $receiverCode)
            ->first();

//                return $receiver->city . ' - ' . $receiver->district . ' - ' . $receiver->neighborhood;

        $control = DB::table('local_locations')
            ->where('city', $receiver->city)
            ->where('district', $receiver->district)
            ->where('neighborhood', $receiver->neighborhood)
            ->first();

        if ($control == null)
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Alıcı için dağıtım yapılmayan bölge: ' . $receiver->neighborhood
                ]);

        $agency = DB::table('agencies')
            ->where('id', $control->agency_code)
            ->first();

        if ($agency->status == '0')
            return response()
                ->json([
                    'status' => 0,
                    'message' => 'Alıcı [' . $agency->agency_name . '] şube pasif olduğundan kargo kesimi gerçekleştiremezsiniz.'
                ]);

        $tc = getTCofAgency($agency->id);

        if ($control->area_type == 'AB')
            $control->area_type = 'Ana Bölge';
        else if ($control->area_type == 'MB')
            $control->area_type = 'Mobil Bölge';

        $array = [
            'status' => 1,
            'arrival_agency' => $agency->agency_name . '-' . $agency->agency_code,
            'arrival_tc' => $tc->tc_name,
            'area_type' => $control->area_type,
        ];

        return response()
            ->json($array, 200);
    }
}
