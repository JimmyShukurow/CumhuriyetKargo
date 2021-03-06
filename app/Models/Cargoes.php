<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Cargoes extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $guarded = [];

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
        'invoice_number',
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
        'kg',
        'cubic_meter_volume',
        'kdv_percent',
        'kdv_price',
        'distance',
        'distance_price',
        'service_price',
        'mobile_service_price',
        'add_service_price',
        'post_service_price',
        'heavy_load_carrying_cost',
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
        'collection_entered',
        'collection_type_entered',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Kargo $eventName.";
    }

    protected function serializeDate(\DateTimeInterface  $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function partDetails()
    {
        return $this->hasMany(CargoPartDetails::class, 'cargo_id', 'id');
    }

    public function bagDetails()
    {
        return $this->belongsToMany(CargoBags::class, 'cargo_bag_details', 'cargo_id', 'bag_id')->wherePivot('is_inside', '1');
    }

    public function cargoCollectionDetails()
    {
        return $this->hasOne(CargoCollection::class, 'cargo_id', 'id');
    }

    public function arrivalBranchAgency()
    {
        if ($this->arrival_agency_code != null && $this->arrival_agency_code != -1) {
            return $this->hasOne(Agencies::class, 'id', 'arrival_agency_code')->withTrashed();
        }
    }

    public function departBranchAgency()
    {
        if ($this->departure_agency_code != null && $this->departure_agency_code != -1) {
            return $this->hasOne(Agencies::class, 'id', 'departure_agency_code')->withTrashed();
        }
    }

    public function getArrivalBranchAgencyNameAttribute()
    {
        return $this->arrivalBranchAgency ? $this->arrivalBranchAgency->agency_name . ' ??UBE' : null;
    }

    public function getDepartureBranchAgencyNameAttribute()
    {
        return $this->departBranchAgency ? $this->departBranchAgency->agency_name . ' ??UBE' : null;
    }
}
