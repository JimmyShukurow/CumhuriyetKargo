<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class StartDateFinishDate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $endDate = "";

    public function __construct($endDate)
    {
        $this->endDate = $endDate;
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
        $end = Carbon::parse($this->endDate);
        $start = Carbon::parse($value);

        $grather = $start->greaterThan($end);

        return $grather;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute değeri başlangıç değerinden büyük olmalıdır!';
    }
}
