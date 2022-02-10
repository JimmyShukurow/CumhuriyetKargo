<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CargoBags extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['type', 'tracking_no', 'creator_user_id', 'status', 'last_opener', 'last_closer'];
    protected static $logAttributes = ['type', 'tracking_no', 'creator_user_id', 'status', 'last_opener', 'last_closer'];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Torba & Çuval $eventName.";
    }

    public function bagDetails()
    {
        return $this
            ->belongsToMany(Cargoes::class, 'cargo_bag_details', 'bag_id', 'cargo_id')
            ->wherePivotNull('deleted_at')
            ->where('is_inside', '=','1')
            ->wherePivotNull('unloaded_time');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
    ];
}
