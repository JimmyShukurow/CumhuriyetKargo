<?php

namespace  App\Http\Resources\CKGMobile\Expedition;

use Illuminate\Http\Resources\Json\JsonResource;

class CargoResource extends JsonResource
{

    public function toArray($request)
    {
        $tracking_no = $this->cargo->tracking_no;
        return [
            'tracking_no_crypted' => crypteTrackingNo($tracking_no. ' '. $this->part_no),
            'receiver_name' => $this->cargo->receiver_name,
            'receiver_address' => $this->cargo->receiver_address,
            'tracking_no' => $this->cargo->tracking_no,
            'number_of_pieces' => $this->cargo->number_of_pieces,
            'part_no' => $this->part_no
        ];
    }
}
