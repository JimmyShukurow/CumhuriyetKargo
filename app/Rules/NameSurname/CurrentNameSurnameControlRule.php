<?php

namespace App\Rules\NameSurname;

use Illuminate\Contracts\Validation\Rule;

class CurrentNameSurnameControlRule implements Rule
{

    public function __construct($messageText = null)
    {
        $this->messageText = $messageText;
    }

    public function passes($attribute, $value)
    {
        if (strlen($value) < 5) {
            $this->messageText = $attribute . ' değeri  toplam 5 karakterden fazla olmalıdır!';
            return false;
        }

        for ($i = 0; $i < strlen($value); $i++) {
            if (is_numeric($value[$i])) {
                $this->messageText = $attribute . ' değeri  rakam içermemelidir!';
                return false;
            }
        }

        $wordCount = explode(' ', $value);
        if (count($wordCount) < 2) {
            $this->messageText = $attribute . ' değeri en az 2 kelime olmalıdır!';
            return false;
        }

        foreach ($wordCount as $key) {
            if (strlen($key) < 2) {
                $this->messageText = $attribute . ' değerindeki kelimeler en 2 harfli olmalıdır!';
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
