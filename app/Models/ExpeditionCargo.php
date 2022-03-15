<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpeditionCargo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function cargo()
    {
        return $this->hasOne(Cargoes::class,'id', 'cargo_id');
    }
}
