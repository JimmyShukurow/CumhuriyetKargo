<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterExpeditionAction
{
    use AsAction;

    public function handle($rows, $departureBranch = null, $type)
    {
        if ($type == "Acente") {
            $rows = $rows->map(function($row)  {
                $routes = clone $row->routes->where('route_type', "1");
                $row->unsetRelation('routes');
                $row->routes = $routes;
                return $row;
            });

            $rows = $rows->filter(function ($item) use ($departureBranch) {
                return $item->routes->filter(function ($key) use ($departureBranch) {
                    return false !== stripos($key->branch->agency_name, tr_strtoupper($departureBranch)) ;
                })->all();
            })->all();
        }

        if ($type == "Aktarma") {
            $rows = $rows->map(function($row)  {
                $routes = clone $row->routes->where('route_type', "1");
                $row->unsetRelation('routes');
                $row->routes = $routes;
                return $row;
            });

            $rows = $rows->filter(function ($item) use ($departureBranch) {
                return $item->routes->filter(function ($key) use ($departureBranch) {
                    return false !== stripos($key->branch->tc_name, tr_strtoupper($departureBranch)) ;
                })->all();
            })->all();
        }

        return collect($rows)->pluck('id');
    }
}
