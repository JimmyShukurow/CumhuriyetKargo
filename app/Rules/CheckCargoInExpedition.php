<?php

namespace App\Rules;

use App\Models\ExpeditionCargo;
use Illuminate\Contracts\Validation\Rule;

class CheckCargoInExpedition implements Rule
{
    public $cargo_id;

    public function __construct($cargo_id)
    {
        $this->cargo_id = $cargo_id;
    }

    public function passes($attribute, $value)
    {
        $cargoes = ExpeditionCargo::where('cargo_id', $this->cargo_id)->where('unloading_at', null)->get()->pluck('part_no');
        $result = $cargoes->contains($value);
        return !$result;
    }


    public function message()
    {
        return 'Bu Kargo Zaten Araçta Var';
    }
}
