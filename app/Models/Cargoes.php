<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Cargoes extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'receiver_id',
        'receiver_name',
        'receiver_phone',
        'receiver_city',
        'receiver_district',
        'receiver_neighborhood',
        'receiver_street',
        'receiver_street2',
        'receiver_building_no',
        'receiver_door_no',
        'receiver_floor',
        'receiver_address_note',
        'receiver_address',
        'sender_id',
        'sender_name',
        'sender_phone',
        'sender_city',
        'sender_district',
        'sender_neighborhood',
        'sender_street',
        'sender_street2',
        'sender_building_no',
        'sender_door_no',
        'sender_floor',
        'sender_address_note',
        'sender_address',
        'customer_code',
        'payment_type',
        'number_of_pieces',
        'cargo_type',
        'cargo_content',
        'cargo_content_ex',
        'tracking_no',
        'arrival_city',
        'arrival_district',
        'arrival_agency_code',
        'arrival_tc_code',
        'departure_city',
        'departure_district',
        'departure_agency_code',
        'departure_tc_code',
        'creator_agency_code',
        'creator_user_id',
        'status',
        'collectible',
        'collection_fee',
        'collection_payment_type',
        'desi',
        'cubic_meter_volume',
        'kdv_percent',
        'kdv_price',
        'distance_price',
        'service_price',
        'add_service_price',
        'total_price',
        'home_delivery',
        'pick_up_address',
        'agency_delivery',
        'cargo_refund',
        'reason_return',
        'status_for_human',
        'confirm',
        'transporter',
        'delivery_code',
        'progress_payment_paid',
        'collection_paid',
        'deleted_by_user_id',
        'delete_reason',
        'system',
    ];

    protected static $logAttributes = [
        'receiver_id',
        'receiver_name',
        'receiver_phone',
        'receiver_city',
        'receiver_district',
        'receiver_neighborhood',
        'receiver_street',
        'receiver_street2',
        'receiver_building_no',
        'receiver_door_no',
        'receiver_floor',
        'receiver_address_note',
        'receiver_address',
        'sender_id',
        'sender_name',
        'sender_phone',
        'sender_city',
        'sender_district',
        'sender_neighborhood',
        'sender_street',
        'sender_street2',
        'sender_building_no',
        'sender_door_no',
        'sender_floor',
        'sender_address_note',
        'sender_address',
        'customer_code',
        'payment_type',
        'number_of_pieces',
        'cargo_type',
        'cargo_content',
        'cargo_content_ex',
        'tracking_no',
        'arrival_city',
        'arrival_district',
        'arrival_agency_code',
        'arrival_tc_code',
        'departure_city',
        'departure_district',
        'departure_agency_code',
        'departure_tc_code',
        'creator_agency_code',
        'creator_user_id',
        'status',
        'collectible',
        'collection_fee',
        'collection_payment_type',
        'desi',
        'cubic_meter_volume',
        'kdv_percent',
        'kdv_price',
        'distance_price',
        'service_price',
        'add_service_price',
        'total_price',
        'home_delivery',
        'pick_up_address',
        'agency_delivery',
        'cargo_refund',
        'reason_return',
        'status_for_human',
        'confirm',
        'transporter',
        'delivery_code',
        'progress_payment_paid',
        'collection_paid',
        'deleted_by_user_id',
        'delete_reason',
        'system',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Kargo $eventName.";
    }


}
