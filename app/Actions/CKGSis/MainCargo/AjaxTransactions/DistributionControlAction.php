<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Agencies;
use App\Models\Currents;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class DistributionControlAction
{
    use AsAction;

    public function handle($request)
    {

        if ($request->gondericiCariKodu == '' || $request->aliciCariKodu == '')
            return ['status' => 0, 'message' => 'Alıcı ve Gönderici cari kodu bilgileri zorunludur!'];

        $currentCode = str_replace(' ', '', $request->gondericiCariKodu);
        $receiverCode = str_replace(' ', '', $request->aliciCariKodu);

        $receiver = Currents::where('current_code', $receiverCode)
            ->first();

        $control = DB::table('local_locations')
            ->where('city', $receiver->city)
            ->where('district', $receiver->district)
            ->where('neighborhood', $receiver->neighborhood)
            ->first();

        $entegration = true;

        if ($control == null)
            if ($entegration)
                return ['status' => 1, 'arrival_agency' => '-', 'arrival_tc' => '-', 'area_type' => 'MNG'];
            else
                return ['status' => 0, 'message' => 'Alıcı için dağıtım yapılmayan bölge: ' . $receiver->neighborhood];


        $agency = Agencies::find($control->agency_code);

        if ($agency->status == '0' || $agency->operation_status == '0')
            if ($entegration)
                return ['status' => 1, 'arrival_agency' => '-', 'arrival_tc' => '-', 'area_type' => 'MNG'];
            else
                return ['status' => 0, 'message' => 'Alıcı [' . $agency->agency_name . '] şube pasif olduğundan kargo kesimi gerçekleştiremezsiniz.'];

        $tc = getTCofAgency($agency->id);

        if ($control->area_type == 'AB')
            $control->area_type = 'Ana Bölge';
        else if ($control->area_type == 'MB')
            $control->area_type = 'Mobil Bölge';

        return $array = [
            'status' => 1,
            'arrival_agency' => $agency->agency_name . '-' . $agency->agency_code,
            'arrival_tc' => $tc->tc_name,
            'area_type' => $control->area_type,
            'arrival_agency_code' => $agency->id,
            'arrival_tc_code' => $tc->id,
        ];
    }
}
