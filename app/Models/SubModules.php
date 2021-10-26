<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class SubModules extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['module_id', 'sub_name', 'must', 'link', 'description'];
    protected static $logAttributes = ['module_id', 'sub_name', 'must', 'link', 'description'];
    protected $table = 'sub_modules';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Alt modül $eventName.";
    }
}
