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
}
