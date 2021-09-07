<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DesiList extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'start_desi',
        'finish_desi',
        'desi_price',
        'corporate_unit_price',
        'individual_unit_price'
    ];

    protected static $logAttributes = [
        'start_desi',
        'finish_desi',
        'desi_price',
        'corporate_unit_price',
        'individual_unit_price'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Desi fiyat aralığı $eventName.";
    }
}
