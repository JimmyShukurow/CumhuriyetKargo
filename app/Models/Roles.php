<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Roles extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = ['name', 'display_name', 'description'];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Yetki $eventName.";
    }

    public function modules()
    {
        return $this->hasMany(RoleModules::class, 'role_id', 'id');
    }
}
