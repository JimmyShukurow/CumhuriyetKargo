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
}
