<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CargoAddServices extends Model
{
    use HasFactory;

    protected $fillable = [
        'cargo_tracking_no',
        'add_service_id',
        'service_name',
        'price',
    ];

}
