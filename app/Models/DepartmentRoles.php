<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DepartmentRoles extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'department_id',
        'role_id'
    ];

    protected static $logAttributes = [
        'department_id',
        'role_id'
    ];
    protected $table  = 'department_roles';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Departmana bağlı yetki $eventName.";
    }
}
