<?php

namespace App\Http\Requests\Expedition;

use App\Rules\CheckCargoInExpedition;
use Illuminate\Foundation\Http\FormRequest;

class LoadCargoToExpeditionRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $cargo_id = $this->request->get('cargo_id');
        return [
            'expedition_id' => 'required|exists:expeditions,id',
            'cargo_id' => 'required',
            'part_no' => ['required', new CheckCargoInExpedition($cargo_id)],
            'user_id' => 'required',
            'unloading_user_id' => 'nullable',
            'unloading_at' => 'nullable',

        ];
    }
    public function messages()
    {
        return [
            'expedition_id.exists' => 'Bu Sefer Mevcut Değil',
        ];
    }


}
