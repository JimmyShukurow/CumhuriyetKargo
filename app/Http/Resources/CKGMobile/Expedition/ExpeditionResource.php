<?php

namespace App\Http\Resources\CKGMobile\Expedition;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpeditionResource extends JsonResource
{

    public function toArray($request)
    {
        $departure_branch = $this->routes->where('route_type', '1')->first()->branch_type == 'Acente' ?
            $this->routes->where('route_type', '1')->first()->branch->agency_name . ' ŞUBE' :
            $this->routes->where('route_type', '1')->first()->branch->tc_name . ' TRM.';

        $arrival_branch = $this->routes->where('route_type', '-1')->first()->branch_type == 'Acente' ?
            $this->routes->where('route_type', '-1')->first()->branch->agency_name . ' ŞUBE' :
            $this->routes->where('route_type', '-1')->first()->branch->tc_name . ' TRM.';
        $inbetweens = $this->routes->where('route_type', 0)->map(function ($q){return ['id'=>$q->id, 'branch'=> $q->branch_details];});
        return [
            'id' => $this->id,
            'plaque' => $this->car->plaka,
            'expedition_no' => $this->serial_no,
            'driver_name' => $this->car->sofor_ad,
            'departure_branch' => $departure_branch,
            'arrival_branch' => $arrival_branch,
            'cargo_count' => $this->cargoes()->count(),
            'inbetweens' => $inbetweens,
        ];
    }
}
