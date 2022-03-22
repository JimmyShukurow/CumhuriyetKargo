<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterIncomingExpeditionsAction
{
    use AsAction;

    public function handle($rows)
    {
        $user = Auth::user();

        $rows = $rows->filter(function ($item) use ($user){
            return $item->routes->filter(function ($key) use ($user) {
                return ($key->route_type == 0 || $key->route_type == -1) && $key->branch_details == $user->branch;
            })->first();
        });

       return  collect($rows);
    }
}
