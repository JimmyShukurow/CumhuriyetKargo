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
                    ->where('name', 'like', '%' . $request->name . '%')
                    ->where('confirmed', '1')
                    ->limit(200)
                    ->orderBy('created_at', 'desc')
                    ->get(['current_type', 'category', 'name', 'gsm', 'city', 'district', 'neighborhood', 'street', 'street2', 'building_no', 'door_no', 'floor', 'address_note', 'current_code as id', 'created_at as reg_date']);

                return response()
                    ->json($Customers, 200);
    }
}
