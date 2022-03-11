<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Expedition extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'serial_no',
        'car_id',
        'user_id',
        'description',
        'done',
        'created_at',
        'updated_at',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Sefer $eventName.";
    }

    public function car()
    {
        return $this->hasOne(TcCars::class, 'id', 'car_id');
    }

    public function departureBranch()
    {
        return $this->hasMany(ExpeditionRoute::class, 'id', 'expedition_id');
    }

}
