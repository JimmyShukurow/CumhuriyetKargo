<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RegionalDistricts extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['region_id', 'city', 'district', 'district_id'];
    protected static $logAttribute = ['region_id', 'city', 'district', 'district_id'];
    protected $table = 'regional_districts';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Bölge müdürlüğüne bağlı ilçe $eventName.";
    }

    public function agencies()
    {
        return $this->hasMany(Agencies::class, 'district_id', 'district_id');
    }




}
