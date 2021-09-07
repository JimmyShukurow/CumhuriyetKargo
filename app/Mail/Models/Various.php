<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Various extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = [
        'brand',
        'model',
        'plaque',
        'tonnage',
        'case_type',
        'model_year',
        'driver_name',
        'phone',
        'city',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Muhtelif araç $eventName.";
    }

    protected $guarded = [];
}
