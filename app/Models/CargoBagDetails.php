<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargoBagDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded=[];

    public function cargo()
    {
        return $this->belongsTo(Cargoes::class, 'cargo_id', 'id');
    }

    public function loaderUser()
    {
        return $this->belongsTo(User::class, 'loader_user_id', 'id');
    }

}
