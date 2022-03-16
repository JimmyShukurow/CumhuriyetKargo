<?php

namespace App\Actions\CKGSis\Marketing\SenderCurrents\AjaxTransaction;

use App\Models\CurrentPrices;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCurrentInfoAction
{
    use AsAction;

    public function handle($request)
    {
        $currentInfo = DB::table('currents')
            ->join('agencies', 'currents.agency', '=', 'agencies.id')
            ->join('view_users_all_info', 'currents.created_by_user_id', '=', 'view_users_all_info.id')
            ->select(['currents.*', 'agencies.agency_name', 'agencies.city as agency_city', 'agencies.district as agency_district', 'agencies.agency_code', 'view_users_all_info.name_surname as creator_user_name', 'view_users_all_info.display_name as creator_display_name'])
            ->where('currents.id', $request->currentID)
            ->first();

        $price = CurrentPrices::where('current_code', $currentInfo->current_code)->first();

        return $jsonData = ['current' => $currentInfo, 'price' => $price];
    }
}
