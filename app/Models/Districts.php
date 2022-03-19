<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    use HasFactory;

    protected $table = 'districts';


    public function city()
    {
        return $this->belongsTo(Cities::class, 'city_id', 'id');
    }


}
