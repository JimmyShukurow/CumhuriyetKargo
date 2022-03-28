<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function bagDetails()
    {
        return $this
            ->belongsToMany(Cargoes::class, 'cargo_bag_details', 'bag_id', 'cargo_id')
            ->wherePivotNull('deleted_at')
            ->where('is_inside', '=', '1')
            ->wherePivotNull('unloaded_time');
    }


    public function bagLastOpener()
    {
        return $this->hasOne(User::class, 'id', 'last_opener');
    }

    public function details()
    {
        return $this->hasMany(CargoBagDetails::class, 'bag_id', 'id');
    }

    public function agency()
    {
        return $this->hasOne(Agencies::class,'id', 'arrival_branch_id');
    }

    public function arrivalBranch()
    {
        if ($this->arrival_branch_model) {
            return $this->morphTo('arrival_branch','arrival_branch_model','arrival_branch_id');
        }elseif ($this->arrival_branch_type == 'Aktarma'){
            return $this->hasOne(TransshipmentCenters::class, 'id', 'arrival_branch_id');
        } elseif ($this->arrival_branch_type == 'Acente') {
            return $this->hasOne(Agencies::class, 'id', 'arrival_branch_id');
        }
    }

    public function getArrivalBranchNameAttribute()
    {
        return $this->arrivalBranch ? ($this->arrivalBranch->agency_name ? $this->arrivalBranch->agency_name . " ŞUBE" : $this->arrivalBranch->tc_name . " TRM.") : null;
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'creator_user_id');
    }
}
