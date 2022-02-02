<?php

namespace App\Actions\CKGSis\MainCargo\AjaxTransactions;

use App\Models\Agencies;
use App\Models\CargoCancellationApplication;
use App\Models\Cargoes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;

class MakeCargoCancellationApplicationAction
{
    use AsAction;

    public function handle($request)
    {
        $rules = [
            'iptal_nedeni' => 'required',
            'id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            return response()->json(['status' => '0', 'errors' => $validator->getMessageBag()->toArray()], 200);

        $agency = Agencies::find(Auth::user()->agency_code);
        $cargo = Cargoes::find($request->id);

        if ($cargo == null || $cargo->creator_agency_code != $agency->id)
            return response()
                ->json([
                    'status' => -1,
                    'message' => 'Kargo Bulunamadı!'
                ], 200);

        $control = DB::table('cargo_cancellation_applications')
            ->where('confirm', '0')
            ->where('cargo_id', $request->id)
            ->count();

        if ($control > 0)
            return response()
                ->json([
                    'status' => -1,
                    'message' => 'Bu kargo için oluşturulmuş sonuç bekleyen bir iptal başvurusu zaten var!'
                ], 200);


        if ($cargo->status_for_human != 'HAZIRLANIYOR')
            return response()
                ->json([
                    'status' => -1,
                    'message' => 'Bu kargo okutma işlemi görmüş, iptal başvurusu yapamazsınız. Lütfen Destek & Ticket üzerinden sistem desteğe durumu iletiniz!'
                ], 200);


        $insert = CargoCancellationApplication::create([
            'cargo_id' => $cargo->id,
            'user_id' => Auth::id(),
            'application_reason' => $request->iptal_nedeni,
            'confirm' => '0',
        ]);

        if ($insert)
            return response()->json(['status' => 1], 200);
        else
            return response()->json(['status' => -1, 'message' => 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'], 200);
    }
}
