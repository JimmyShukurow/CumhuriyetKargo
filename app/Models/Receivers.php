<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Receivers extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'vkn',
        'tckn',
        'name_surname_company',
        'name',
        'company_name',
        'city',
        'district',
        'neighborhood',
        'street',
        'street2',
        'building_no',
        'door_no',
        'floor',
        'address_note',
        'receiver_agency_id',
        'phone',
        'gsm',
        'email',
        'category',
        'current_code',
        'created_by_user_id'
    ];

    protected static $logAttributes = [
        'vkn',
        'tckn',
        'name_surname_company',
        'name',
        'company_name',
        'city',
        'district',
        'neighborhood',
        'street',
        'street2',
        'building_no',
        'door_no',
        'floor',
        'address_note',
        'receiver_agency_id',
        'phone',
        'gsm',
        'email',
        'category',
        'current_code',
        'created_by_user_id'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Alıcı cari $eventName.";
    }
}
