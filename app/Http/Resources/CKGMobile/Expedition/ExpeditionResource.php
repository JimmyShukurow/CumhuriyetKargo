<?php

namespace App\Http\Resources\CKGMobile\Expedition;

use App\Http\Resources\CarResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpeditionResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'car' => new CarResource($this->car),
            'cargo_count' => $this->cargoes()->count()

        ];
    }
}
