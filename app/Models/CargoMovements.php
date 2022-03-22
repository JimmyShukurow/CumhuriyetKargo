<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoMovements extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->select(['id', 'name_surname', 'user_type', 'role_id', 'agency_code', 'tc_code']);
    }

    public function cargo()
    {
        return $this->hasOne(Cargoes::class, 'id', 'cargo_id')
            ->select(['id', 'number_of_pieces'])
            ->withTrashed();
    }
}
