<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Tickets extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'title',
        'department_id',
        'priority',
        'message',
        'file1',
        'file2',
        'file3',
        'file4',
        'redirected',
        'status'
    ];

    protected static $logAttributes = [
        'user_id',
        'title',
        'department_id',
        'priority',
        'message',
        'file1',
        'file2',
        'file3',
        'file4',
        'redirected',
        'status'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Ticket $eventName.";
    }

    public function lastReply()
    {
        return $this->hasOne(TicketDetails::class, 'ticket_id', 'id')
            ->orderBy('id', 'desc');
    }

    public function replies()
    {
        return $this->hasMany(TicketDetails::class, 'ticket_id', 'id');
    }


    protected $table = 'tickets';
}
