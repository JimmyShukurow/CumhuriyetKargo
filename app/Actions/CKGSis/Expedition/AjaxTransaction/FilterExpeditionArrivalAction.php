<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use Lorisleiva\Actions\Concerns\AsAction;

class FilterExpeditionArrivalAction
{
    use AsAction;

    public function handle($rows, $arrivalBranch = null, $type)
    {
        if ($type == "Acente") {
            $rows = $rows->map(function($row)  {
                $routes = clone $row->routes->where('route_type', "-1");
                $row->unsetRelation('routes');
                $row->routes = $routes;
                return $row;
            });

            $rows = $rows->filter(function ($item) use ($arrivalBranch) {
                return $item->routes->filter(function ($key) use ($arrivalBranch) {
                    return false !== stripos($key->branch->agency_name, tr_strtoupper($arrivalBranch)) ;
                })->all();
            })->all();
        }

        if ($type == "Aktarma") {
            $rows = $rows->map(function($row)  {
                $routes = clone $row->routes->where('route_type', "-1");
                $row->unsetRelation('routes');
                $row->routes = $routes;
                return $row;
            });

            $rows = $rows->filter(function ($item) use ($arrivalBranch) {
                return $item->routes->filter(function ($key) use ($arrivalBranch) {
                    return false !== stripos($key->branch->tc_name, tr_strtoupper($arrivalBranch)) ;
                })->all();
            })->all();
        }

        return collect($rows)->pluck('id');
    }
}
