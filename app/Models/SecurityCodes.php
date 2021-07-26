<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SecurityCodes extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'code',
        'status'
    ];

    protected static $logAttributes = [
        'user_id',
        'code',
        'status'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Güvenlik kodu $eventName.";
    }

    protected $table = 'security_codes';
}
