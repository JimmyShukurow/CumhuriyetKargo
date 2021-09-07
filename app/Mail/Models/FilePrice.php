<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class FilePrice extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'corporate_file_price',
        'individual_file_price',
    ];

    protected static $logAttributes = [
        'corporate_file_price',
        'individual_file_price',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Dosya ücretleri $eventName.";
    }

}
