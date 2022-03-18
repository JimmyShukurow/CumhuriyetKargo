<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VknConfirmlRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($vkn, $taxOfficeCity, $taxOffice)
    {
        $this->vkn = $vkn;
        $this->taxOfficeCity = $taxOfficeCity;
        $this->taxOffice = $taxOffice;
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
        $result = vkn_confirm($this->vkn, $this->taxOffice, $this->taxOfficeCity);
        return $result['status'] == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Kurumsal kişinin bilgileri Gelir İdaresi Başkanlığı Tarafından Onaylanmadı, Lütfen bilgileri kontrol edip tekrar deneyiniz!';
    }
}
