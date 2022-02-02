<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Currents;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCustomerAction
{
    use AsAction;

    public function handle($request)
    {
        $request->currentCode = str_replace(' ', '', $request->currentCode);
        $customer = null;
        if ($request->type == 'receiver')
            $customer = Currents::where('current_code', $request->currentCode)
                ->select(['name', 'current_type', 'category', 'tckn', 'vkn', 'current_code', 'gsm', 'address_note', 'street', 'street2', 'city', 'district', 'neighborhood', 'building_no', 'door_no', 'floor', 'address_note'])
                ->where('confirmed', '1')
                ->first();

        else if ($request->type == 'current')
            $customer = Currents::where('current_code', $request->currentCode)
                ->select(['name', 'current_type', 'category', 'tckn', 'vkn', 'current_code', 'gsm', 'address_note', 'street', 'street2', 'city', 'district', 'neighborhood', 'building_no', 'door_no', 'floor', 'address_note'])
                ->where('confirmed', '1')
                ->first();

        if ($customer == null)
            return response()
                ->json(['status' => -1], 200);

        return response()
            ->json($customer, 200);

    }
}
