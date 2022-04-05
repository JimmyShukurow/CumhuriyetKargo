<?php

namespace App\Rules\NameSurname;

use Illuminate\Contracts\Validation\Rule;

class CurrentNameControlRule implements Rule
{

    public function __construct($messageText = null)
    {
        $this->messageText = $messageText;
    }

    public function passes($attribute, $value)
    {
        if (strlen($value) < 2) {
            $this->messageText = $attribute . ' değeri toplam 2 karakterden fazla olmalıdır!';
            return false;
        }

        for ($i = 0; $i < strlen($value); $i++) {
            if (is_numeric($value[$i])) {
                $this->messageText = $attribute . ' değeri rakam içermemelidir!';
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return $this->messageText;
    }
}
