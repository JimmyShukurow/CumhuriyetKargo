<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AgencyPayment extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'id',
        'row_type',
        'app_id',
        'user_id',
        'description',
        'agency_id',
        'payment',
        'payment_channel',
        'paying_name_surname',
        'payment_date',
        'created_at',
        'updated_at',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Acente ödemesi $eventName.";
    }
}
