<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentSms extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'heading',
        'subject',
        'sms_content',
        'phone',
        'length',
        'quantity',
        'causer_user_id',
        'ip_address',
        'ctn',
        'result',
    ];
}
