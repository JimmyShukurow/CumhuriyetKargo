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
            'ctn' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'expedition_id.exists' => 'Bu Sefer Mevcut Değil',
        ];
    }


}
