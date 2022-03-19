<?php

namespace App\Actions\CKGSis\Customer\AjaxTransaction;

use Lorisleiva\Actions\Concerns\AsAction;

class GetTaxOfficeWithCodeAction
{
    use AsAction;

    public function handle($plaque, $vd_code)
    {
        $json = collect(json_decode(TaxOfficesAction::jsonData()));

        $plaque = $plaque;

        switch (strlen($plaque)) {
            case 1:
                $plaque = '00' . $plaque;
                break;

            case 2:
                $plaque = '0' . $plaque;
                break;

            case 3:
                $plaque = $plaque;
                break;
        }

        return collect($json->toArray()[$plaque])->where('code', '=', $vd_code)->first()->tax_office;
    }
}
