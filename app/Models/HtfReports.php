<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class HtfReports extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected static $logAttributes = [
        'id',
        'report_serial_no',
        'cargo_id',
        'cargo_invoice_number',
        'cargo_tracking_no',
        'real_detecting_unit_type',
        'detecting_user_id',
        'reported_unit_type',
        'real_reported_unit_type',
        'reported_unit_id',
        'damage_description',
        'content_detection',
        'confirm',
        'reject_reason',
        'confirming_user_id',
        'confirming_datetime',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Acente $eventName.";
    }
}
