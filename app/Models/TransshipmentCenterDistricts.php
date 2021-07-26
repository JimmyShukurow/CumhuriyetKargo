<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TransshipmentCenterDistricts extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'tc_id',
        'city',
        'district'
    ];

    protected static $logAttributes = [
        'tc_id',
        'city',
        'district'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Transfer merkezine bağlı ilçe $eventName.";
    }

    protected $table = "transshipment_center_districts";
}
