<?php

namespace App\Rules;

use App\Models\Districts;
use App\Models\Neighborhoods;
use Illuminate\Contracts\Validation\Rule;

class CityDistrictRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($city = null, $district = null)
    {
        $this->city = $city;
        $this->district = $district;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $district = Districts::with('city')
            ->where('id', $this->district)->first();

        if ($district == null)
            return false;


        return ($district->id == $this->district) && ($district->city->id == $this->city);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Lütfen geçerli bir il ve ilçe seçiniz!';
    }
}
