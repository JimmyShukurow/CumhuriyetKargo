<?php

namespace App\Actions\CKGSis\Safe\GeneralSafe;

use App\Models\Agencies;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class GetAgencySafeStatusDetailsAction
{
    use AsAction;

    public function handle($request)
    {

        $id = $request->id;
        if ($id == null)
            return response()
                ->json(['status' => -1, 'message' => 'ID alanı gereklidir!'], 200);


        $agency = Agencies::find($id);

        if ($agency == null)
            return response()
                ->json(['status' => -1, 'message' => 'Acente bulunamadı!'], 200);


        $data['agency'] = DB::table('view_agency_safe_status')
            ->where('id', $id)
            ->first();


        $data['agency']->endorsement = getDotter($data['agency']->endorsement);
        $data['agency']->cash_amount = getDotter($data['agency']->cash_amount);
        $data['agency']->pos_amount = getDotter($data['agency']->pos_amount);
        $data['agency']->intraday = getDotter($data['agency']->intraday);
        $data['agency']->amount_deposited = getDotter($data['agency']->amount_deposited);
        $data['agency']->debt = getDotter($data['agency']->debt);


        return response()
            ->json(['status' => 1, 'data' => $data], 200);

    }
}
