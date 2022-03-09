<?php

namespace App\Http\Requests;

use App\Rules\StartDateFinishDate;
use App\Rules\TcArrayControl;
use App\Rules\TcControl;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FieldsToUppercaseTrait;
use Illuminate\Support\Facades\Auth;

class TransferCarsRequest extends FormRequest
{

use FieldsToUppercaseTrait;

    public function rules()
    {
        return [
            'plaka' => 'required|unique:tc_cars',
            'marka' => 'required',
            'model' => 'required',
            'model_yili' => 'required',
            'arac_kapasitesi' => 'required',
            'tonaj' => 'required',
            'desi_kapasitesi' => 'required|numeric',
            'arac_takip_sistemi' => 'required',
            'hat' => 'required',
            'cikis_aktarma' => ['required', 'numeric', new TcControl],
            'varis_aktarma' => ['required', 'numeric', new TcControl],
            'ugradigi_aktarmalar' => ['required', new TcArrayControl],
            'muayene_baslangic_tarihi' => 'required',
            'muayene_bitis_tarihi' => ['required', new StartDateFinishDate($this->muayene_baslangic_tarihi)],
            'trafik_sigortasi_baslangic_tarihi' => 'required',
            'trafik_sigortasi_bitis_tarihi' => ['required', new StartDateFinishDate($this->trafik_sigortasi_baslangic_tarihi)],
            'sofor_ad' => 'required',
            'sofor_telefon' => 'required',
            'sofor_adres' => 'required',
            'arac_sahibi_ad' => 'required',
            'arac_sahibi_telefon' => 'required',
            'arac_sahibi_adres' => 'required',
            'arac_sahibi_yakini_ad' => 'required',
            'arac_sahibi_yakini_telefon' => 'required',
            'arac_sahibi_yakini_adres' => 'required',
            'car_type' => 'nullable',
        ];
    }
}
