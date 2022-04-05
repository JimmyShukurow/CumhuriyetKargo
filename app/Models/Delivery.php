<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Delivery extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'cargo_id',
        'user_id',
        'agency_id',
        'description',
        'receiver_name_surname',
        'receiver_tckn_vkn',
        'degree_of_proximity',
        'delivery_date',
        'transfer_reason',
        'status',
        'created_at',
        'updated_at',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Acente $eventName.";
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->select(['id', 'name_surname', 'role_id'])
            ->withTrashed();
    }

    public function agency()
    {
        return $this->hasOne(Agencies::class, 'id', 'agency_id')
            ->select(['id', 'agency_name', 'agency_code'])
            ->withTrashed();
    }

    public function deliveryParts()
    {
        return $this->hasMany(DeliveryDetail::class, 'delivery_id', 'id')
            ->select(['id', 'delivery_id', 'part_no']);
    }


}
