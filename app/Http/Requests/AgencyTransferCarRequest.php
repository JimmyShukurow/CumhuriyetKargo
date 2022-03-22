<?php

namespace App\Http\Requests;

use App\Traits\FieldsToUppercaseTrait;
use Illuminate\Foundation\Http\FormRequest;

class AgencyTransferCarRequest extends FormRequest
{

    use FieldsToUppercaseTrait;

    protected function prepareForValidation()
    {
        $this->merge([
            'plaka' => str_replace(' ', '', $this->plaka),
        ]);
    }

    public function rules()
    {
        $rules = [
            'plaka' => 'required|unique:tc_cars',
            'marka' => 'required',
            'model' => 'required',
            'model_yili' => 'required',
            'car_type' => 'required',
            'branch_code' => 'required',
            'doors_to_be_sealed' => 'required|integer|min:1',
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
        ];

        $method = $this->method();

        if ($method == 'POST'){
            return array_merge($rules, ['plaka' => 'required|unique:tc_cars,plaka']);
        }
        if ($method == 'PUT'){
            return  array_merge($rules, ['plaka' => 'required']);
        }
    }


    public function messages()
    {
        return [
            'doors_to_be_sealed.integer' => 'Mühür Vurulacak Kapı Sayısı Tam Sayı Olmalı.',
            'doors_to_be_sealed.required' => 'Mühür Vurulacak Kapı Sayısı Dolu olucak.',
            'model_yili.integer' => 'Model Yılı Tam Sayı Olucak.',
            'doors_to_be_sealed.min' => 'Kapı Sayısı Sıfırdan Büyük Olucak',
        ];
    }

}
