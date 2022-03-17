<?php

namespace App\Actions\CKGSis\Marketing\PriceDrafts;

use App\Models\PriceDrafts;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPriceDraftAction
{
    use AsAction;

    public function handle($request)
    {
        $draft = PriceDrafts::find($request->id);

        if ($draft != null)
            return response()->json(['status' => 1, 'data' => $draft], 200);
        else
            return response()->json(['status' => -1, 'message' => 'Fiyat taslağı bulunamadı!'], 200);
    }
}
