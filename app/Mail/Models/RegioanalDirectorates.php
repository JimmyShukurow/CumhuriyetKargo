<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RegioanalDirectorates extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'phone',
        'city',
        'district',
        'neighborhood',
        'adress',
        'director_id',
        'assistant_director_id'
    ];

    protected static $logAttributes = [
        'name',
        'phone',
        'city',
        'district',
        'neighborhood',
        'adress',
        'director_id',
        'assistant_director_id'
    ];

    protected $table = 'regional_directorates';

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Bölge müdürlüğü $eventName.";
    }
}
