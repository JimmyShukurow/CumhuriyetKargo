<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpeditionCargo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    protected function serializeDate(\DateTimeInterface  $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function cargo()
    {
        return $this->hasOne(Cargoes::class,'id', 'cargo_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function unloadedUser()
    {
        return $this->hasOne(User::class, 'id', 'unloading_user_id');
    }

    public function deletedUser()
    {
        return $this->hasOne(User::class, 'id', 'deleted_user_id');
    }

    public function CargoPartDetailsTotal($id)
    {
        return self::where('cargo_id', $this->cargo_id)
            ->where('expedition_id', $id)
            ->where('unloading_user_id', null)
            ->where('unloading_at', null)
            ->get()->count();
    }

    public function expedition()
    {
        return $this->belongsTo(Expedition::class, 'id', 'expedition_id');
    }

    public function cargosLoadedRoute()
    {
        return $this->hasOne(ExpeditionRoute::class, 'id', 'expedition_route_id');
    }
}
