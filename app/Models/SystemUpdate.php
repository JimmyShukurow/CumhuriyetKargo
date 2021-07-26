<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SystemUpdate extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'version',
        'title',
        'content',
    ];

    protected static $logAttributes = [
        'version',
        'title',
        'content',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Version güncellemesi $eventName.";
    }
}
