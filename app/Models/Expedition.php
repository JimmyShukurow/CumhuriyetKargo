<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Expedition extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select(['users.id', 'users.name_surname', 'display_name'])
            ->withTrashed();
    }

    public function routes()
    {
        return $this->hasMany(ExpeditionRoute::class, 'expedition_id', 'id');
    }

    public function cargoes(){
        return $this->hasMany(ExpeditionCargo::class, 'expedition_id', 'id')
            ->where('unloading_at', null);
    }

    public function allCargoes()
    {
        return $this->hasMany(ExpeditionCargo::class, 'expedition_id', 'id')->withTrashed();
    }

}
