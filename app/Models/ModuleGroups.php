<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ModuleGroups extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'must',
        'description'
    ];
    protected static $logAttributes = ['title', 'must', 'description'];
    protected static $logFillable = true;

    protected $table = 'module_groups';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Modül grubu $eventName.";
    }
}
