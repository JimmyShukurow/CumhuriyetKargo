<?php

namespace App\Http\Requests\Expedition;

use Illuminate\Foundation\Http\FormRequest;

class LoadCargoToExpeditionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'expedition_id' => 'required',
            'cargo_id' => 'required',
            'part_no' => 'required',
            'user_id' => 'required',
            'unloading_user_id' => 'nullable',
            'unloading_at' => 'nullable',

        ];
    }
}
