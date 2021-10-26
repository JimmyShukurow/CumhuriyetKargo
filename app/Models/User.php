<?php

namespace App\Models;

use http\Env\Response;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity, SoftDeletes, HasApiTokens;

    protected static $logAttributes = [
        'id',
        'name_surname',
        'email',
        'password',
        'phone',
        'role_id',
        'agency_code',
        'mac_adress',
        'status',
        'status_description',
        'user_image',
        'agency_code',
        'tc_code',
        'user_type',
        'creator_user',
        'deleting_user'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name_surname',
        'email',
        'password',
        'phone',
        'role_id',
        'agency_code',
        'mac_adress',
        'status',
        'status_description',
        'user_image',
        'agency_code',
        'tc_code',
        'user_type',
        'creator_user',
        'deleting_user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        $eventName = getLocalEventName($eventName);
        return "Kullanıcı $eventName.";
    }


    public function authUserToken()
    {
        return $this->hasMany('App\Models\OauthAccessToken');
    }


}
