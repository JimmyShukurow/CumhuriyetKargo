<?php

namespace App\Actions\CKGSis\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAllCustomersAction
{
    use AsAction;

    public function handle($request)
    {
        $category = $request->category;
        $city = $request->city;
        $currentCode = str_replace([' ', '_'], ['', ''], $request->currentCode);
        $customer_name_surname = tr_strtoupper($request->customer_name_surname);
        $customer_type = $request->customer_type;
        $phone = $request->phone;

        $data = DB::table('currents')
            ->select('currents.*', 'users.name_surname')
            ->join('users', 'users.id', '=', 'currents.created_by_user_id')
            ->join('agencies', 'agencies.id', '=', 'users.agency_code')
            ->whereRaw($category ? "category='" . $category . "'" : '1 > 0')
            ->whereRaw($currentCode ? 'current_code=' . $currentCode : '1 > 0')
            ->whereRaw($city ? "city='" . $city . "'" : '1 > 0')
            ->whereRaw($customer_name_surname ? "name like '%" . $customer_name_surname . "%'" : '1 > 0')
            ->whereRaw($customer_type ? "current_type='" . $customer_type . "'" : '1 > 0')
            ->whereRaw($phone ? "gsm='" . $phone . "'" : '1 > 0')
            ->where('agency', Auth::user()->agency_code)
            ->whereRaw('currents.deleted_at is null');

        return datatables()->of($data)
            ->editColumn('current_code', function ($current) {
                return '<b class="customer-detail" id="' . $current->id . '" style="text-decoration:underline; color:#000; cursor:pointer; user-select:none">' . CurrentCodeDesign($current->current_code) . '</b>';
            })
            ->editColumn('free', function ($current) {
                return '';
            })
            ->addColumn('edit', 'backend.customers.agency.columns.edit')
            ->rawColumns(['edit', 'current_code'])
            ->make(true);
    }
}
