<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Currents extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'current_code',
        'current_type',
        'category',
        'name',
        'authorized_name',
        'tax_administration',
        'vkn',
        'tckn',
        'city',
        'district',
        'neighborhood',
        'address_note',
        'door_no',
        'floor',
        'building_no',
        'street',
        'street2',
        'phone',
        'phone2',
        'gsm',
        'gsm2',
        'email',
        'web_site',
        'dispatch_city',
        'dispatch_district',
        'dispatch_post_code',
        'dispatch_adress',
        'status',
        'agency',
        'departure_all_agencies',
        'bank_iban',
        'bank_owner_name',
        'discount',
        'reference',
        'confirmed',
        'confirmed_by_user_id',
        'created_by_user_id',
        'contract_start_date',
        'contract_end_date',
        'mb_status',
    ];

    protected static $logAttributes = [
        'current_code',
        'current_type',
        'category',
        'name',
        'authorized_name',
        'tax_administration',
        'vkn',
        'tckn',
        'city',
        'district',
        'neighborhood',
        'address_note',
        'door_no',
        'floor  ',
        'building_no',
        'street',
        'street2',
        'phone',
        'phone2',
        'gsm',
        'gsm2',
        'email',
        'web_site',
        'dispatch_city',
        'dispatch_district',
        'dispatch_post_code',
        'dispatch_adress',
        'status',
        'agency',
        'departure_all_agencies',
        'bank_iban',
        'bank_owner_name',
        'discount',
        'reference',
        'confirmed',
        'confirmed_by_user_id',
        'created_by_user_id',
        'contract_start_date',
        'contract_end_date',
        'mb_status',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Cari $eventName.";
    }

    public function getUserById()
    {
        return User::find($this->created_by_user_id)->name_surname;
    }

    public function cargoesAsReciever()
    {
        return $this->hasMany(Cargoes::class, 'receiver_id', 'id');
    }

    public function cargoesAsSender()
    {
     return $this->hasMany(Cargoes::class, 'sender_id', 'id');
    }

}
