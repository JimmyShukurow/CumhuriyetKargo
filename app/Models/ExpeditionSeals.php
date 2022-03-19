<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpeditionSeals extends Model
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function openedUser()
    {
        return $this->hasOne(User::class, 'id', 'opening_user_id');
    }

    public function expedition()
    {
        return $this->belongsTo(Expedition::class, 'id', 'expedition_id');
    }

    public function getCreatorAttribute()
    {
        return $this->user->name_role;
    }

    public function getOpenerAttribute()
    {
        return $this->openedUser->name_role;
    }
}
