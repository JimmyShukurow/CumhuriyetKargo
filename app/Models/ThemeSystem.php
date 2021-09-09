<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class ThemeSystem extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    protected $table = 'theme_system';
}
