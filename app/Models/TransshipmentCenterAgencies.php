<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TransshipmentCenterAgencies extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'tc_id',
        'agency_id'
    ];

    protected static $logAttributes = [
        'tc_id',
        'agency_id'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Transfer Merkezine bağlı acente $eventName.";
    }


    protected $table = 'transshipment_center_agencies';
}
