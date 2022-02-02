<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CargoBagDetailsResource extends JsonResource
{
   
    public function toArray($request)
    {
        return [
            'cargo_id' => $this->id,
            'ctn' => $this->tracking_no,
            'part_no' => $this->partDetails->pluck('part_no'),

        ];
    }
}
