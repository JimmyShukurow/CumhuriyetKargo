<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class TcArrayControl implements Rule
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
//        $value = "1, 2, 3, 4, 5, 6,";
        $value = substr($value, 0, strlen($value) - 1);
        $array = explode(',', $value);

        foreach ($array as $key){
            $exist = DB::table('transshipment_centers')
                ->where('id', $value)
                ->whereRaw('deleted_at is null')
                ->first();

            return $exist != null;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute Toplu gönderilen transfer merkezleri geçerli bir biçimde değil!';
    }
}
