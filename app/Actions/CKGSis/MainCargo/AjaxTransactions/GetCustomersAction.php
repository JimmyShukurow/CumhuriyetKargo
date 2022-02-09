<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Currents;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCustomersAction
{
    use AsAction;

    public function handle($request)
    {
        $Customers = DB::table('currents')
            ->whereRaw($request->name != null ? "name like '%" . $request->name . "%'" : ' 1 > 0')
            ->whereRaw($request->phone != null ? "gsm like '%" . $request->phone . "%'" : ' 1 > 0')
            ->where('confirmed', '1')
            ->limit(300)
            ->orderBy('created_at', 'desc')
            ->get(['current_type', 'category', 'name', 'gsm', 'city', 'district', 'neighborhood', 'street', 'street2', 'building_no', 'door_no', 'floor', 'address_note', 'current_code as id', 'created_at as reg_date']);

        return response()
            ->json($Customers, 200);
    }
}
