<?php

namespace App\Http\Requests\Tutorail;

use Illuminate\Foundation\Http\FormRequest;

class TutorialRequest extends FormRequest
{

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
