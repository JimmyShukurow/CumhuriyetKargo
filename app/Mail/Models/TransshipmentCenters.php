<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TransshipmentCenters extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable
        = ['id', 'tc_name', 'phone', 'type', 'city', 'district', 'neighborhood', 'adress', 'status', 'status_description', 'tc_director_id', 'tc_assistant_director_id'];
    protected static $logAttributes
        = ['id', 'tc_name', 'phone', 'type', 'city', 'district', 'neighborhood', 'adress', 'status', 'status_description', 'tc_director_id', 'tc_assistant_director_id'];
    protected $table = 'transshipment_centers';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Transfer Merkezi $eventName.";
    }

}
