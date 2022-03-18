<?php

namespace App\Http\Requests\Marketing\PriceDraft;

use App\Rules\PriceControl;
use Illuminate\Foundation\Http\FormRequest;

class PriceDraftRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            "DraftName" => 'required',
            "FilePrice" => ['required', new PriceControl()],
            "MiPrice" => ['required', new PriceControl()],
            "Desi1_5" => ['required', new PriceControl()],
            "Desi6_10" => ['required', new PriceControl()],
            "Desi11_15" => ['required', new PriceControl()],
            "Desi16_20" => ['required', new PriceControl()],
            "Desi21_25" => ['required', new PriceControl()],
            "Desi26_30" => ['required', new PriceControl()],
            "AmountOfIncrease" => ['required', new PriceControl()],
            "AgencyPermission" => 'required',
        ];
    }

    public function messages()
    {
        return [
            "DraftName.required" => 'Taslak adı alanı gereklidir!',
            "FilePrice.required" => 'Dosya Fiyatı alanı gereklidir!',
            "MiPrice.required" => 'Mi Fiyatı alanı gereklidir!',
            "Desi1_5.required" => 'Desi 1 5 alanı gereklidir!',
            "Desi6_10.required" => 'Desi 6 10 alanı gereklidir!',
            "Desi11_15.required" => 'Desi 11 15 alanı gereklidir!',
            "Desi16_20.required" => 'Desi 16 20 alanı gereklidir!',
            "Desi21_25.required" => 'Desi 21 25 alanı gereklidir!',
            "Desi26_30.required" => 'Desi 26 30 alanı gereklidir!',
            "AmountOfIncrease.required" => '30 Üstü Artış Fiyatı alanı gereklidir!',
            "AgencyPermission.required" => 'Acente İzin alanı gereklidir!',
        ];
    }
}
