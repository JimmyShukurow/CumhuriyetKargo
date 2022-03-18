<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TcknConfirmlRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($name, $surname, $bornYear, $tckn)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->bornYear = $bornYear;
        $this->tckn = $tckn;
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
        $bilgiler = ["isim" => $this->name, "soyisim" => $this->surname, "dogumyili" => $this->bornYear, "tcno" => $this->tckn];
        $sonuc = tcno_dogrula($bilgiler);

        return !($sonuc == "false");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Kişinin kimlik bilgileri hatalıdır! Lütfen TC, Ad, Soyad ve Doğum Yılı bilgilerini kontrol ediniz.';
    }
}
