<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CargoBagResource extends JsonResource
{
    
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'tracking_no' => $this->tracking_no,
            'creator_user_id' => $this->creator_user_id,
            'status' => $this->status,
            'last_opener' => $this->last_opener,
            'last_closer' => $this->last_closer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
