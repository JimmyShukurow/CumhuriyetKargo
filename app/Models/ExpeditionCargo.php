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

    public function cargo()
    {
        return $this->hasOne(Cargoes::class,'id', 'cargo_id');
    }
}
