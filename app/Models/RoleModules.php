<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RoleModules extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['role_id', 'sub_module_id'];
    protected static $logAttributes = ['role_id', 'sub_module_id'];
    protected $table = 'role_modules';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Yetkiye bağlı modül $eventName.";
    }

    public function subModule()
    {
        return $this->hasOne(SubModules::class, 'id', 'sub_module_id');
    }
}
