<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class PriceDrafts extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'user_id',
        'name',
        'file',
        'mi',
        'd_1_5',
        'd_6_10',
        'd_11_15',
        'd_16_20',
        'd_21_25',
        'd_26_30',
        'amount_of_increase',
        'agency_permission',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Fiyat taslağı $eventName.";
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
