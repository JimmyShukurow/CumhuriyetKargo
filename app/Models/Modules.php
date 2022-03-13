<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Modules extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name', 'ico', 'module_group_id', 'must', 'link', 'description'
    ];

    protected static $logAttributes = [
        'name', 'ico', 'module_group_id', 'must', 'link', 'description'
    ];
    protected $table = 'modules';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Modül $eventName.";
    }

    public function moduleGroup()
    {
        return $this->hasOne(ModuleGroups::class, 'id', 'module_group_id');
    }
}
