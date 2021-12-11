<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Agencies extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'name_surname',
        'city',
        'district',
        'neighborhood',
        'agency_name',
        'adress',
        'phone',
        'phone2',
        'transshipment_center_code',
        'agency_development_officer',
        'status',
        'status_description',
        'agency_code'
    ];

    protected static $logAttributes = [
        'name_surname',
        'city',
        'district',
        'neighborhood',
        'agency_name',
        'adress',
        'phone',
        'phone2',
        'transshipment_center_code',
        'agency_development_officer',
        'status',
        'status_description',
        'agency_code'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Acente $eventName.";
    }

    protected $table = 'agencies';
}
