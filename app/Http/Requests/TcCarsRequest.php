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
            'model_yili' => 'required|Integer',
            'car_type' => 'required',
            'branch_code' => 'required',
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
            'doors_to_be_sealed' => 'required|Integer',
        ];
    }
    public function messages()
    {
        return [
            'doors_to_be_sealed.integer' => 'Mühür Vurulacak Kapı Sayısı Tam Sayı Olmalı.',
            'doors_to_be_sealed.required' => 'Mühür Vurulacak Kapı Sayısı Dolu olucak.',
            'model_yili.integer' => 'Model Yılı Tam Sayı Olucak.',
        ];
    }

}
