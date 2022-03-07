<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgencyTransferCarRequest extends FormRequest
{

    protected function prepareForValidation()
    {
        $this->merge([
            'plaka' => tr_strtoupper($this->plaka),
            'marka' => tr_strtoupper($this->marka),
            'model' => tr_strtoupper($this->model),
            'sofor_ad' => tr_strtoupper($this->sofor_ad),
        ]);
    }

    public function rules()
    {
        return [
            'plaka' => 'required',
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
