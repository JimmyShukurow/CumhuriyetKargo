<?php

namespace App\Models;

use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpeditionRoute extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function branch()
    {
        return $this->morphTo('branch','branch_model','branch_code');
    }
}
