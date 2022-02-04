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
            'crypted_ctn' => crypteTrackingNo($this->tracking_no . ' 1'),
            'part_no' => 1,
            'user_id' => $this->creator_user_id,
            'agency_code' => $this->creator_agency_code,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'receiver_name' => $this->receiver_name,
            'receiver_address' => $this->receiver_address,
            'receiver_city' => $this->receiver_city,
            'recevier_district' => $this->receiver_district,
        ];
    }
}
