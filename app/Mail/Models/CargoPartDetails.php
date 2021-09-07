<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoPartDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'cargo_id',
        'tracking_no',
        'part_no',
        'width',
        'size',
        'height',
        'weight',
        'desi',
        'cubic_meter_volume',
    ];
}
