<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class AdditionalServices extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'service_name',
        'price',
        'status'
    ];

    protected static $logAttributes = [
        'service_name',
        'price',
        'status'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Ek hizmet $eventName.";
    }


}
