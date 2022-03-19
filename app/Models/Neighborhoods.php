<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhoods extends Model
{
    use HasFactory;

    protected $table = 'neighborhoods';

    public function district()
    {
        return $this->hasOne(Districts::class, 'id', 'district_id');
    }

}
