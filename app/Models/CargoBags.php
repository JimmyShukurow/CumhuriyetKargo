<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CargoBags extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['type', 'tracking_no', 'creator_user_id', 'status', 'last_opener', 'last_closer'];
    protected static $logAttributes = ['type', 'tracking_no', 'creator_user_id', 'status', 'last_opener', 'last_closer'];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Torba & Çuval $eventName.";
    }

}
