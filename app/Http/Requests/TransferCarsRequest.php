<?php

namespace App\Http\Requests;

use App\Models\TcCars;
use App\Rules\StartDateFinishDate;
use App\Rules\TcArrayControl;
use App\Rules\TcControl;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FieldsToUppercaseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransferCarsRequest extends FormRequest
{

use FieldsToUppercaseTrait;


    public function rules()
    {
        $rules = [
            'marka' => 'required',
            'model' => 'required',
            'model_yili' => 'required',
            'arac_kapasitesi' => 'nullable',
            'tonaj' => 'nullable',
            'desi_kapasitesi' => 'nullable|numeric',
            'arac_takip_sistemi' => 'nullable',
            'hat' => 'nullable',
            'cikis_aktarma' => ['nullable', 'numeric', new TcControl],
            'varis_aktarma' => ['nullable', 'numeric', new TcControl],
            'ugradigi_aktarmalar' => ['nullable', new TcArrayControl],
            'muayene_baslangic_tarihi' => 'nullable',
            'muayene_bitis_tarihi' => ['nullable', new StartDateFinishDate($this->muayene_baslangic_tarihi)],
            'trafik_sigortasi_baslangic_tarihi' => 'nullable',
            'trafik_sigortasi_bitis_tarihi' => ['nullable', new StartDateFinishDate($this->trafik_sigortasi_baslangic_tarihi)],
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
            'arac_sahibi_ad' => 'nullable',
            'arac_sahibi_telefon' => 'nullable',
            'arac_sahibi_adres' => 'nullable',
            'arac_sahibi_yakini_ad' => 'nullable',
            'arac_sahibi_yakini_telefon' => 'nullable',
            'arac_sahibi_yakini_adres' => 'nullable',
            'car_type' => 'nullable',
            'status' => 'nullable',
            'branch_code' =>'required',
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
            'branch_code.required' => 'Bağlı Olduğuğ Şube Bilgisi Zorunludur.',
        ];
    }
}
