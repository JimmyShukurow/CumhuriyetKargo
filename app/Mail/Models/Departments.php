<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Departments extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'department_name',
        'explantion'
    ];

    protected static $logAttributes = [
        'department_name',
        'explantion'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Departman $eventName.";
    }

    protected $table = 'departments';
}
