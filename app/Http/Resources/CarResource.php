<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'plaque' => $this->plaka,
            'driver_name' => $this->sofor_ad,
        ];
    }
}
