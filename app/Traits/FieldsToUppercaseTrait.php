<?php

namespace App\Traits;

trait FieldsToUppercaseTrait {
    protected function prepareForValidation()
    {
        $this->merge([
            'plaka' => tr_strtoupper($this->plaka),
            'marka' => tr_strtoupper($this->marka),
            'model' => tr_strtoupper($this->model),
            'sofor_ad' => tr_strtoupper($this->sofor_ad),
        ]);
    }
}