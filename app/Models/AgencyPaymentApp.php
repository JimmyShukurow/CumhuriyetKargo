<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class AgencyPaymentApp extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'id',
        'agency_id',
        'user_id',
        'paid',
        'confirm_paid',
        'payment_channel',
        'file1',
        'file2',
        'file3',
        'description',
        'currency',
        'confirm',
        'confirming_by_user_id',
        'confirming_date',
        'reject_reason',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Ödeme bildirgesi $eventName.";
    }

}
