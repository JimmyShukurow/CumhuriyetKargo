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


        return response()
            ->json(['status' => 1, 'data' => $data], 200);

    }
}
