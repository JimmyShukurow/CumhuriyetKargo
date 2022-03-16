<?php

namespace App\Rules;

use App\Models\Cities;
use App\Models\Neighborhoods;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CityDistrictNeighborhoodRule implements Rule
{

    public function __construct($city = null, $district = null, $neighborhood = null)
    {
        $this->city = $city;
        $this->district = $district;
        $this->neighborhood = $neighborhood;
    }

    public function passes($attribute, $value)
    {
        $neighborhood = Neighborhoods::with(['district.city'])
            ->where('id', '=', $this->neighborhood)->first();

        if ($neighborhood == null)
            return false;

        return ($neighborhood->id == $this->neighborhood)
            && ($neighborhood->district->id == $this->district)
            &&   ($neighborhood->district->city->id == $this->city);
    }

    public function message()
    {
        return 'Lütfen geçerli bir il, ilçe ve mahalle seçiniz!';
    }
}
