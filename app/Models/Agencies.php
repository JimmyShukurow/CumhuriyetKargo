<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpseclib3\Math\PrimeField\Integer;
use Spatie\Activitylog\Traits\LogsActivity;

class Agencies extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $guarded = [];

    protected static $logAttributes = [
        'name_surname',
        'city',
        'district',
        'neighborhood',
        'agency_name',
        'adress',
        'phone',
        'phone2',
        'phone3',
        'transshipment_center_code',
        'agency_development_officer',
        'maps_link',
        'ip_address',
        'permission_of_create_cargo',
        'safe_status',
        'safe_status_description',
        'status',
        'status_description',
        'agency_code',
        'maps_link'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Acente $eventName.";
    }

    protected $table = 'agencies';

    public function address()
    {
        return $this->hasMany(Districts::class, 'id', 'district_id');
    }

    public function relatedCargoes()
    {
        return $this->hasMany(Cargoes::class, 'departure_agency_code', 'id')
            ->select([
                'id',
                'total_price',
                'cargo_type',
                'desi',
                'total_price',
                'created_at',
                'departure_agency_code',
            ]);
    }

    public function relatedUser()
    {
        return $this->hasOne(User::class, 'agency_code', 'id');
    }

    public function endorsementWithDate($firstDate, $lastDate)
    {
        return $this->hasMany(Cargoes::class, 'departure_agency_code', 'id')
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->sum('total_price');
    }

    public function cargoCountWithDate($firstDate, $lastDate)
    {
        return $this->hasMany(Cargoes::class, 'departure_agency_code', 'id')
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->count();
    }

    public function cargoCargoCountWithDate($firstDate, $lastDate)
    {
        return $this->hasMany(Cargoes::class, 'departure_agency_code', 'id')
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->whereNotIn('cargo_type', ['Dosya', 'Mi'])
            ->count();
    }

    public function cargoDsAmountWithDate($firstDate, $lastDate)
    {
        return $this->hasMany(Cargoes::class, 'departure_agency_code', 'id')
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->sum('desi');
    }

    public function cargoFileCountWithDate($firstDate, $lastDate)
    {
        return $this->hasMany(Cargoes::class, 'departure_agency_code', 'id')
            ->whereBetween('created_at', [$firstDate, $lastDate])
            ->whereIn('cargo_type', ['Dosya', 'Mi'])
            ->count();
    }

    public function personelCount()
    {
        return $this->hasOne(User::class, 'agency_code', 'id')
            ->count();
    }

    public function region()
    {
        return $this->hasOne(RegionalDistricts::class, 'district_id', 'district_id')
            ->join('regional_directorates', 'region_id', '=', 'regional_directorates.id');
    }

    public function tc()
    {
        return $this->hasOne(TransshipmentCenterDistricts::class, 'district_id', 'district_id')
            ->join('transshipment_centers', 'tc_id', '=', 'transshipment_centers.id');
    }

    public function localLocations()
    {
        return $this->hasMany(LocalLocation::class, 'agency_code');
    }

}
