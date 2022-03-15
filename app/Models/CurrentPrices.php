<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CurrentPrices extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'current_code',
        'file',
        'mi',
        'd_1_5',
        'd_6_10',
        'd_11_15',
        'd_16_20',
        'd_21_25',
        'd_26_30',
        'amount_of_increase',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Cari fiyatı $eventName.";
    }

}
