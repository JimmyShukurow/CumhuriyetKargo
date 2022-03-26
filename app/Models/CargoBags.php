<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder\Class_;
use Spatie\Activitylog\Traits\LogsActivity;

class CargoBags extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];
    protected static $logAttributes = ['type', 'tracking_no', 'creator_user_id', 'arrival_branch_id', 'arrival_branch_type', 'status', 'last_opener', 'last_closer'];

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
            ->where('is_inside', '=', '1')
            ->wherePivotNull('unloaded_time');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'last_opening_date' => 'datetime:Y-m-d H:m:s',
    ];

    public function bagLastOpener()
    {
        return $this->hasOne(User::class, 'id', 'last_opener');
    }

    public function details()
    {
        return $this->hasMany(CargoBagDetails::class, 'bag_id', 'id');
    }

    public function arrivalBranch()
    {
        return $this->morphTo('arrival_branch','arrival_branch_model','arrival_branch_id');
    }
}
