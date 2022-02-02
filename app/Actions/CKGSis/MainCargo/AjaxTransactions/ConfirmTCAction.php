<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use Lorisleiva\Actions\Concerns\AsAction;

class ConfirmTCAction
{
    use AsAction;

    public function handle($request)
    {
        $bilgiler = array(
            "isim" => $request->ad,
            "soyisim" => $request->soyad,
            "dogumyili" => $request->dogum_tarihi,
            "tcno" => $request->tc,
        );

        $sonuc = tcno_dogrula($bilgiler);

        if ($sonuc == "true")
            return response()
                ->json(array('status' => 1), 200);
        else
            return response()
                ->json(array(
                    'status' => 0,
                    'message' => 'Bilgiler hatalıdır! Lütfen TC, Ad, Soyad ve Doğum Yılı bilgilerini kontrol ediniz.'),
                    200);
    }
}
