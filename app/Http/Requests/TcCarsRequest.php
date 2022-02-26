<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TcCarsRequest extends FormRequest
{
   
    public function rules()
    {
        return [
            'plaka' => 'required',
            'marka' => 'required',
            'model' => 'required',
            'model_yili' => 'required',
            'car_type' => 'required',
            'branch_code' => 'required',
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
        ];
    }
}
