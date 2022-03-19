<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneControlRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $gsm = CharacterCleaner($value);
        return !(strlen($gsm) != 10);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute alanı başında 0 olmadan 10 hane olacak şekilde girilmelidir!';
    }
}
