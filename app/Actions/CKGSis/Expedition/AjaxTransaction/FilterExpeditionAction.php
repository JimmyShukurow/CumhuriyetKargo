<?php

namespace App\Actions\CKGSis\Expedition\AjaxTransaction;

use App\Models\Expedition;
use Lorisleiva\Actions\Concerns\AsAction;

class FilterExpeditionAction
{
    use AsAction;

    public function handle($rows, $departureBranch = null, $arrivalBranch = null)
    {
        $rows = clone $rows;

        $rows = $rows->map(function($row){
             $routes = clone $row->routes->where('route_type', '1');
            $row->unsetRelation('routes');
            $row->routes = $routes;
            return $row;
        });

        $rows = $rows->filter(function ($item) use ($departureBranch) {
            return $item->routes->filter(function ($key) use ($departureBranch) {
                return false !== stripos($key->branch->agency_name, tr_strtoupper($departureBranch)) ;
            })->all();
        })->all();

//        $new2 = clone $rows;
//
//        $new2 = $new2->map(function($row){
//            $routes = clone $row->routes->where('route_type', '1');
//            $row->unsetRelation('routes');
//            $row->routes = $routes;
//            return $row;
//        });
//
//        $new2 = $new2->filter(function ($item) use ($departureBranch) {
//            return $item->routes->filter(function ($key) use ($departureBranch) {
//                return false !== stripos($key->branch->agency_name, tr_strtoupper($departureBranch)) ;
//            })->all();
//        })->all();

        return collect($rows)->pluck('id');
    }
}
