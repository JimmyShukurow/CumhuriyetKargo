<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\CurrentPrices;
use App\Models\Currents;
use Lorisleiva\Actions\Concerns\AsAction;

class CalcCollectionPercentAction
{
    use AsAction;

    public function handle($request)
    {
        $price = doubleval(getDoubleValue($request->collectionPrice));
                $interruption = 0;

                if ($request->currentCode == '')
                    return response()
                        ->json(['status' => -1, 'message' => 'Kesinti Oranını hesaplamak için öncelikle göndericiyi ve alıcıyı girin.'], 200);

                $current = Currents::where('current_code', str_replace(' ', '', $request->currentCode))->first();

                if ($current === null)
                    return response()
                        ->json(['status' => -1, 'message' => 'Gönderici bulunamadı, cari kodunun doğru olduğundan emin olun!'], 200);

                $state = CurrentControl($current->current_code);

                if ($state['status'] != 1)
                    return response()
                        ->json(['status' => -1, 'message' => 'Gönderici hatalı! ' . $state['result']], 200);

                if ($current->category == 'Kurumsal' && $current->current_type == 'Gönderici') {

                    $currentPrices = CurrentPrices::where('current_code', $current->current_code)->first();

                    if ($price > 0 && $price <= 200)
                        $interruption = $currentPrices->collect_price;
                    else if ($price == 0)
                        $interruption = 0;
                    else {
                        $interruptionPercent = $currentPrices->collect_amount_of_increase;
                        $interruption = ($price * $interruptionPercent) / 100;
                    }

                    return response()
                        ->json(['status' => 1, 'interruption' => '₺ ' . $interruption, 'to_be_paid' => '₺ ' . ($price - $interruption)], 200);

//                    return $interruption;
//                    dd($currentPrices);


                } else
                    return response()
                        ->json(['status' => -1, 'message' => 'Göndericinin anlaşması yok, tahsilatlı kargo çıkaramazsınız!'], 200);

                return $request->all();
    }
}
