<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TicketDetails extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'file1',
        'file2',
        'file3',
        'file4'
    ];

    protected static $logAttributes = [
        'ticket_id',
        'user_id',
        'message',
        'file1',
        'file2',
        'file3',
        'file4'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Ticket detayı $eventName.";
    }

    protected $table = 'ticket_details';

}
