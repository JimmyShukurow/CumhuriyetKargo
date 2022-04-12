<?php

namespace App\Http\Requests\Tutorail;

use Illuminate\Foundation\Http\FormRequest;

class TutorialRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $index = strpos($this->embedded_link,'v=');

        $code = substr($this->embedded_link, $index+2, 100);

        $this->merge([
            'plaka' => 'https://www.youtube.com/embed/'.$code,
        ]);
    }
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'name' => 'required',
            'category' => 'required',
            'embedded_link' => 'required',
            'tutor' => 'required',
            'description' => 'nullable',
        ];
    }
}
